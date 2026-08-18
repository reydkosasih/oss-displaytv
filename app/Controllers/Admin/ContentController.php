<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContentModel;
use App\Models\CategoryModel;
use App\Models\ContentChartModel;
use App\Models\TvCategoryModel;
use App\Models\TvUpdateEventModel;
use App\Libraries\AuditLogger;

class ContentController extends BaseController
{
    protected $contentModel;
    protected $categoryModel;
    protected $contentChartModel;
    protected $tvCategoryModel;
    protected $tvEventModel;

    public function __construct()
    {
        $this->contentModel      = new ContentModel();
        $this->categoryModel     = new CategoryModel();
        $this->contentChartModel = new ContentChartModel();
        $this->tvCategoryModel   = new TvCategoryModel();
        $this->tvEventModel      = new TvUpdateEventModel();
    }

    /**
    /**
     * Helper: Ambil ID Kategori yang diizinkan untuk user saat ini (null jika Superadmin)
     */
    protected function getAllowedCategoryIds(): ?array
    {
        $role   = session()->get('role');
        $userId = (int) session()->get('user_id');

        if ($role === 'superadmin') {
            return null; // Superadmin memiliki akses ke seluruh kategori
        }

        $userCategoryModel = new \App\Models\UserCategoryModel();
        return $userCategoryModel->getCategoryIdsByUser($userId);
    }

    /**
     * Helper: Cek apakah user memiliki hak akses ke category_id tertentu
     */
    protected function checkCategoryPermission(int $categoryId): bool
    {
        $allowed = $this->getAllowedCategoryIds();
        if ($allowed === null) {
            return true;
        }
        return in_array($categoryId, $allowed, true);
    }

    /**
     * Tampilkan halaman utama Kelola Konten
     */
    public function index()
    {
        $allowedCatIds = $this->getAllowedCategoryIds();
        if ($allowedCatIds === null) {
            $categories = $this->categoryModel->orderBy('name', 'ASC')->findAll();
        } elseif (empty($allowedCatIds)) {
            $categories = [];
        } else {
            $categories = $this->categoryModel->whereIn('id', $allowedCatIds)->orderBy('name', 'ASC')->findAll();
        }

        return view('admin/content/index', [
            'title'      => 'Kelola Konten Media',
            'categories' => $categories,
        ]);
    }

    /**
     * Endpoint AJAX: List data konten (JSON) + detail kategori & URL file / JSON Chart
     */
    public function list()
    {
        $db    = \Config\Database::connect();
        $type  = $this->request->getGet('type'); // 'image' | 'video' | 'chart' | null
        $catId = $this->request->getGet('category_id');

        $allowedCatIds = $this->getAllowedCategoryIds();

        if ($allowedCatIds !== null) {
            if (empty($allowedCatIds)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'data'   => []
                ]);
            }
            if ($catId && !in_array((int) $catId, $allowedCatIds, true)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'data'   => []
                ]);
            }
        }

        $builder = $db->table('contents c')
                      ->select('c.*, cat.name as category_name, cat.color as category_color')
                      ->join('categories cat', 'cat.id = c.category_id')
                      ->where('c.deleted_at', null);

        if ($allowedCatIds !== null && !$catId) {
            $builder->whereIn('c.category_id', $allowedCatIds);
        }

        if ($type) {
            $builder->where('c.type', $type);
        }
        if ($catId) {
            $builder->where('c.category_id', $catId);
        }

        $contents = $builder->orderBy('c.created_at', 'DESC')->get()->getResultArray();

        foreach ($contents as &$cnt) {
            if ($cnt['type'] === 'image' && $cnt['file_path']) {
                $cnt['file_url'] = base_url('uploads/contents/images/' . $cnt['file_path']);
            } elseif ($cnt['type'] === 'video') {
                if ($cnt['video_source'] === 'upload' && $cnt['file_path']) {
                    $cnt['file_url'] = base_url('uploads/contents/videos/' . $cnt['file_path']);
                } elseif ($cnt['video_source'] === 'youtube' && $cnt['youtube_url']) {
                    $cnt['youtube_id'] = $this->extractYoutubeId($cnt['youtube_url']);
                }
            } elseif ($cnt['type'] === 'chart') {
                $chartRow = $this->contentChartModel->getByContentId((int) $cnt['id']);
                if ($chartRow) {
                    $cnt['chart_type']     = $chartRow['chart_type'];
                    $cnt['chart_labels']   = json_decode($chartRow['chart_labels'], true);
                    $cnt['chart_datasets'] = json_decode($chartRow['chart_datasets'], true);
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $contents
        ]);
    }

    /**
     * Endpoint AJAX: Simpan konten Gambar baru
     */
    public function storeImage()
    {
        $rules = [
            'title'                    => 'required|min_length[2]|max_length[150]',
            'category_id'              => 'required|numeric',
            'display_duration_seconds' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $categoryId = (int) $this->request->getPost('category_id');

        if (!$this->checkCategoryPermission($categoryId)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => ['category_id' => 'Anda tidak memiliki akses ke kategori ini.']
            ]);
        }

        $file = $this->request->getFile('image_file');
        if (!$file || !$file->isValid()) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => ['image_file' => 'File gambar wajib diunggah.']
            ]);
        }

        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => ['image_file' => 'Format file harus berupa JPG, PNG, atau WEBP.']
            ]);
        }

        $uploadPath = ROOTPATH . 'public/uploads/contents/images/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $imageResult = $this->processAndSaveWebp($file, $uploadPath);
        $fileName    = $imageResult['file_path'];
        $fileSize    = $imageResult['file_size'];

        $categoryId = (int) $this->request->getPost('category_id');

        $data = [
            'category_id'              => $categoryId,
            'type'                     => 'image',
            'title'                    => trim($this->request->getPost('title')),
            'file_path'                => $fileName,
            'file_size'                => $fileSize,
            'display_duration_seconds' => (int) $this->request->getPost('display_duration_seconds'),
            'is_active'                => $this->request->getPost('is_active') ? 1 : 0,
            'created_by'               => session()->get('user_id'),
        ];

        $this->contentModel->insert($data);
        $this->notifyTvsByCategory($categoryId);

        // Audit Log: Buat konten gambar
        AuditLogger::log('create', 'content', "Menambahkan konten gambar: {$data['title']}", [
            'entity_name' => $data['title'],
            'new_values'  => array_diff_key($data, ['created_by' => '']),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Konten gambar berhasil ditambahkan.'
        ]);
    }

    /**
     * Endpoint AJAX: Simpan konten Video baru (Upload / YouTube)
     */
    public function storeVideo()
    {
        $source = $this->request->getPost('video_source');

        $rules = [
            'title'        => 'required|min_length[2]|max_length[150]',
            'category_id'  => 'required|numeric',
            'video_source' => 'required|in_list[upload,youtube]',
        ];

        if ($source === 'youtube') {
            $rules['youtube_url']            = 'required|valid_url';
            $rules['video_duration_seconds'] = 'required|numeric|greater_than[0]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $categoryId = (int) $this->request->getPost('category_id');

        if (!$this->checkCategoryPermission($categoryId)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => ['category_id' => 'Anda tidak memiliki akses ke kategori ini.']
            ]);
        }

        $data = [
            'category_id'  => $categoryId,
            'type'         => 'video',
            'title'        => trim($this->request->getPost('title')),
            'video_source' => $source,
            'is_active'    => $this->request->getPost('is_active') ? 1 : 0,
            'created_by'   => session()->get('user_id'),
        ];

        if ($source === 'upload') {
            $file = $this->request->getFile('video_file');
            if (!$file || !$file->isValid()) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status' => 'error',
                    'errors' => ['video_file' => 'File video MP4/WEBM wajib diunggah.']
                ]);
            }

            $fileName   = $file->getRandomName();
            $fileSize   = $file->getSize();
            $uploadPath = ROOTPATH . 'public/uploads/contents/videos/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $fileName);

            $data['file_path'] = $fileName;
            $data['file_size'] = $fileSize;

            $detectedDuration = null;
            if (class_exists('getID3')) {
                try {
                    $getID3   = new \getID3();
                    $fileInfo = $getID3->analyze($uploadPath . $fileName);
                    if (isset($fileInfo['playtime_seconds']) && $fileInfo['playtime_seconds'] > 0) {
                        $detectedDuration = (int) round($fileInfo['playtime_seconds']);
                    }
                } catch (\Exception $e) {}
            }

            $manualDuration = (int) $this->request->getPost('video_duration_seconds');
            $duration = $detectedDuration ?: ($manualDuration > 0 ? $manualDuration : 30);

            $data['video_duration_seconds']  = $duration;
            $data['display_duration_seconds'] = $duration;

        } elseif ($source === 'youtube') {
            $youtubeUrl = trim($this->request->getPost('youtube_url'));
            $youtubeId  = $this->extractYoutubeId($youtubeUrl);

            if (!$youtubeId) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status' => 'error',
                    'errors' => ['youtube_url' => 'URL YouTube tidak valid atau ID tidak ditemukan.']
                ]);
            }

            $duration = (int) $this->request->getPost('video_duration_seconds');

            $data['youtube_url']             = $youtubeUrl;
            $data['video_duration_seconds']  = $duration;
            $data['display_duration_seconds'] = $duration;
        }

        $this->contentModel->insert($data);
        $this->notifyTvsByCategory($categoryId);

        // Audit Log: Buat konten video
        $sourceLabel = $data['video_source'] === 'youtube' ? 'YouTube' : 'Upload';
        AuditLogger::log('create', 'content', "Menambahkan konten video ({$sourceLabel}): {$data['title']}", [
            'entity_name' => $data['title'],
            'new_values'  => array_diff_key($data, ['created_by' => '']),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Konten video berhasil ditambahkan.'
        ]);
    }

    /**
     * Endpoint AJAX: Batch upload multiple media files (Images / Videos)
     */
    public function storeBatch()
    {
        $files = $this->request->getFileMultiple('batch_files');
        if (!$files || empty($files)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Tidak ada file yang diunggah.'
            ]);
        }

        $titles      = $this->request->getPost('titles') ?? [];
        $categoryIds = $this->request->getPost('category_ids') ?? [];
        $durations   = $this->request->getPost('durations') ?? [];
        $isActives   = $this->request->getPost('is_actives') ?? [];

        $successCount   = 0;
        $failedCount    = 0;
        $errors         = [];
        $notifiedCatIds = [];

        $imgUploadPath = ROOTPATH . 'public/uploads/contents/images/';
        if (!is_dir($imgUploadPath)) {
            mkdir($imgUploadPath, 0777, true);
        }

        $vidUploadPath = ROOTPATH . 'public/uploads/contents/videos/';
        if (!is_dir($vidUploadPath)) {
            mkdir($vidUploadPath, 0777, true);
        }

        foreach ($files as $idx => $file) {
            if (!$file->isValid() || $file->hasMoved()) {
                $failedCount++;
                $errors[] = "File #" . ($idx + 1) . " tidak valid atau sudah dipindahkan.";
                continue;
            }

            $title      = !empty($titles[$idx]) ? trim($titles[$idx]) : pathinfo($file->getClientName(), PATHINFO_FILENAME);
            $categoryId = !empty($categoryIds[$idx]) ? (int)$categoryIds[$idx] : 0;
            $duration   = !empty($durations[$idx]) ? (int)$durations[$idx] : 10;
            $isActive   = isset($isActives[$idx]) ? ((int)$isActives[$idx] === 1 ? 1 : 0) : 1;

            if ($categoryId <= 0) {
                $failedCount++;
                $errors[] = "Kategori untuk file '{$title}' wajib dipilih.";
                continue;
            }

            if (!$this->checkCategoryPermission($categoryId)) {
                $failedCount++;
                $errors[] = "Anda tidak memiliki akses ke kategori untuk file '{$title}'.";
                continue;
            }

            $mime = $file->getMimeType();
            $allowedImageMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            $allowedVideoMimes = ['video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo'];

            if (in_array($mime, $allowedImageMimes, true) || str_contains($mime, 'image')) {
                // Process Image
                $imageResult = $this->processAndSaveWebp($file, $imgUploadPath);
                $data = [
                    'category_id'              => $categoryId,
                    'type'                     => 'image',
                    'title'                    => $title,
                    'file_path'                => $imageResult['file_path'],
                    'file_size'                => $imageResult['file_size'],
                    'display_duration_seconds' => $duration > 0 ? $duration : 10,
                    'is_active'                => $isActive,
                    'created_by'               => session()->get('user_id'),
                ];
                $this->contentModel->insert($data);
                $successCount++;
                $notifiedCatIds[$categoryId] = true;
            } elseif (in_array($mime, $allowedVideoMimes, true) || str_contains($mime, 'video')) {
                // Process Video
                $fileName = $file->getRandomName();
                $fileSize = $file->getSize();
                $file->move($vidUploadPath, $fileName);

                $detectedDuration = null;
                if (class_exists('getID3')) {
                    try {
                        $getID3   = new \getID3();
                        $fileInfo = $getID3->analyze($vidUploadPath . $fileName);
                        if (isset($fileInfo['playtime_seconds']) && $fileInfo['playtime_seconds'] > 0) {
                            $detectedDuration = (int) round($fileInfo['playtime_seconds']);
                        }
                    } catch (\Exception $e) {}
                }

                $finalDuration = $detectedDuration ?: ($duration > 0 ? $duration : 30);
                $data = [
                    'category_id'              => $categoryId,
                    'type'                     => 'video',
                    'title'                    => $title,
                    'video_source'             => 'upload',
                    'file_path'                => $fileName,
                    'file_size'                => $fileSize,
                    'video_duration_seconds'  => $finalDuration,
                    'display_duration_seconds' => $finalDuration,
                    'is_active'                => $isActive,
                    'created_by'               => session()->get('user_id'),
                ];
                $this->contentModel->insert($data);
                $successCount++;
                $notifiedCatIds[$categoryId] = true;
            } else {
                $failedCount++;
                $errors[] = "Format file '{$file->getClientName()}' tidak didukung (harus Gambar atau Video).";
            }
        }

        // Trigger SSE notifications for updated categories
        foreach (array_keys($notifiedCatIds) as $catId) {
            $this->notifyTvsByCategory($catId);
        }

        if ($successCount === 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal mengunggah semua file.',
                'errors'  => $errors
            ]);
        }

        // Audit Log: Batch upload
        AuditLogger::log('create', 'content', "Batch upload: {$successCount} konten media berhasil ditambahkan" . ($failedCount > 0 ? ", {$failedCount} gagal" : ''), [
            'new_values' => ['success_count' => $successCount, 'failed_count' => $failedCount, 'categories' => array_keys($notifiedCatIds)],
        ]);

        return $this->response->setJSON([
            'status'        => 'success',
            'message'       => "Berhasil mengunggah {$successCount} konten media." . ($failedCount > 0 ? " ({$failedCount} gagal)" : ''),
            'success_count' => $successCount,
            'failed_count'  => $failedCount,
            'errors'        => $errors
        ]);
    }


    /**
     * Endpoint AJAX: Simpan konten Chart baru
     */
    public function storeChart()
    {
        $rules = [
            'title'                    => 'required|min_length[2]|max_length[150]',
            'category_id'              => 'required|numeric',
            'chart_type'               => 'required|in_list[bar,line,pie]',
            'display_duration_seconds' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $categoryId = (int) $this->request->getPost('category_id');

        if (!$this->checkCategoryPermission($categoryId)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => ['category_id' => 'Anda tidak memiliki akses ke kategori ini.']
            ]);
        }

        $labels = $this->request->getPost('labels') ?? [];
        $values = $this->request->getPost('values') ?? [];

        // Filter out empty rows
        $cleanLabels = [];
        $cleanValues = [];
        for ($i = 0; $i < count($labels); $i++) {
            $lbl = trim($labels[$i]);
            if ($lbl !== '') {
                $cleanLabels[] = $lbl;
                $cleanValues[] = (float) ($values[$i] ?? 0);
            }
        }

        if (empty($cleanLabels)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => ['labels' => 'Minimal 1 baris label & nilai data chart wajib diisi.']
            ]);
        }

        $data = [
            'category_id'              => $categoryId,
            'type'                     => 'chart',
            'title'                    => trim($this->request->getPost('title')),
            'display_duration_seconds' => (int) $this->request->getPost('display_duration_seconds'),
            'is_active'                => $this->request->getPost('is_active') ? 1 : 0,
            'created_by'               => session()->get('user_id'),
        ];

        $contentId = $this->contentModel->insert($data);

        // Prepare Chart JSON Data
        $chartLabelName = trim($this->request->getPost('dataset_label')) ?: 'Data Chart';
        $chartColor     = $this->request->getPost('dataset_color') ?: '#3b82f6';

        $datasets = [
            [
                'label' => $chartLabelName,
                'data'  => $cleanValues,
                'color' => $chartColor,
            ]
        ];

        $this->contentChartModel->insert([
            'content_id'     => $contentId,
            'chart_type'     => $this->request->getPost('chart_type'),
            'chart_labels'   => json_encode($cleanLabels),
            'chart_datasets' => json_encode($datasets),
        ]);

        $this->notifyTvsByCategory($categoryId);

        // Audit Log: Buat konten chart
        AuditLogger::log('create', 'content', "Menambahkan konten chart: {$data['title']}", [
            'entity_id'   => $contentId,
            'entity_name' => $data['title'],
            'new_values'  => array_diff_key($data, ['created_by' => '']),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Konten chart berhasil ditambahkan.'
        ]);
    }

    /**
     * Endpoint AJAX: Detail Konten (JSON)
     */
    public function getJson($id = null)
    {
        $content = $this->contentModel->find($id);

        if (!$content) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Konten tidak ditemukan.'
            ]);
        }

        if (!$this->checkCategoryPermission((int) $content['category_id'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke konten kategori ini.'
            ]);
        }

        if ($content['type'] === 'image' && $content['file_path']) {
            $content['file_url'] = base_url('uploads/contents/images/' . $content['file_path']);
        } elseif ($content['type'] === 'video') {
            if ($content['video_source'] === 'upload' && $content['file_path']) {
                $content['file_url'] = base_url('uploads/contents/videos/' . $content['file_path']);
            } elseif ($content['video_source'] === 'youtube' && $content['youtube_url']) {
                $content['youtube_id'] = $this->extractYoutubeId((string) $content['youtube_url']);
            }
        } elseif ($content['type'] === 'chart') {
            $chartRow = $this->contentChartModel->getByContentId((int) $id);
            if ($chartRow) {
                $content['chart_type']     = $chartRow['chart_type'];
                $content['chart_labels']   = json_decode($chartRow['chart_labels'], true);
                $content['chart_datasets'] = json_decode($chartRow['chart_datasets'], true);
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $content
        ]);
    }

    /**
     * Endpoint AJAX: Update Konten Gambar
     */
    public function updateImage($id = null)
    {
        $content = $this->contentModel->find($id);

        if (!$content) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Konten tidak ditemukan.'
            ]);
        }

        if (!$this->checkCategoryPermission((int) $content['category_id'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke konten kategori ini.'
            ]);
        }

        $rules = [
            'title'                    => 'required|min_length[2]|max_length[150]',
            'category_id'              => 'required|numeric',
            'display_duration_seconds' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $categoryId = (int) $this->request->getPost('category_id');

        if (!$this->checkCategoryPermission($categoryId)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => ['category_id' => 'Anda tidak memiliki akses ke kategori ini.']
            ]);
        }

        $data = [
            'category_id'              => $categoryId,
            'title'                    => trim($this->request->getPost('title')),
            'display_duration_seconds' => (int) $this->request->getPost('display_duration_seconds'),
            'is_active'                => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $file = $this->request->getFile('image_file');
        if ($file && $file->isValid()) {
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status' => 'error',
                    'errors' => ['image_file' => 'Format file harus berupa JPG, PNG, atau WEBP.']
                ]);
            }

            $uploadPath = ROOTPATH . 'public/uploads/contents/images/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $imageResult       = $this->processAndSaveWebp($file, $uploadPath);
            $data['file_path'] = $imageResult['file_path'];
            $data['file_size'] = $imageResult['file_size'];
        }

        $this->contentModel->update($id, $data);
        $this->notifyTvsByCategory($categoryId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Konten gambar berhasil diperbarui.'
        ]);
    }

    /**
     * Endpoint AJAX: Update Konten Video
     */
    public function updateVideo($id = null)
    {
        $content = $this->contentModel->find($id);

        if (!$content) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Konten tidak ditemukan.'
            ]);
        }

        if (!$this->checkCategoryPermission((int) $content['category_id'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke konten kategori ini.'
            ]);
        }

        $source = $this->request->getPost('video_source') ?: $content['video_source'];

        $rules = [
            'title'        => 'required|min_length[2]|max_length[150]',
            'category_id'  => 'required|numeric',
            'video_source' => 'required|in_list[upload,youtube]',
        ];

        if ($source === 'youtube') {
            $rules['youtube_url']            = 'required|valid_url';
            $rules['video_duration_seconds'] = 'required|numeric|greater_than[0]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $categoryId = (int) $this->request->getPost('category_id');

        if (!$this->checkCategoryPermission($categoryId)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => ['category_id' => 'Anda tidak memiliki akses ke kategori ini.']
            ]);
        }

        $data = [
            'category_id'  => $categoryId,
            'title'        => trim($this->request->getPost('title')),
            'video_source' => $source,
            'is_active'    => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($source === 'upload') {
            $file = $this->request->getFile('video_file');
            if ($file && $file->isValid()) {
                $fileName   = $file->getRandomName();
                $fileSize   = $file->getSize();
                $uploadPath = ROOTPATH . 'public/uploads/contents/videos/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                $file->move($uploadPath, $fileName);

                $data['file_path'] = $fileName;
                $data['file_size'] = $fileSize;

                if (class_exists('getID3')) {
                    try {
                        $getID3   = new \getID3();
                        $fileInfo = $getID3->analyze($uploadPath . $fileName);
                        if (isset($fileInfo['playtime_seconds']) && $fileInfo['playtime_seconds'] > 0) {
                            $dur = (int) round($fileInfo['playtime_seconds']);
                            $data['video_duration_seconds']  = $dur;
                            $data['display_duration_seconds'] = $dur;
                        }
                    } catch (\Exception $e) {}
                }
            }

            $manualDuration = (int) $this->request->getPost('video_duration_seconds');
            if ($manualDuration > 0) {
                $data['video_duration_seconds']  = $manualDuration;
                $data['display_duration_seconds'] = $manualDuration;
            }

        } elseif ($source === 'youtube') {
            $youtubeUrl = trim($this->request->getPost('youtube_url'));
            $youtubeId  = $this->extractYoutubeId($youtubeUrl);

            if (!$youtubeId) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status' => 'error',
                    'errors' => ['youtube_url' => 'URL YouTube tidak valid atau ID tidak ditemukan.']
                ]);
            }

            $duration = (int) $this->request->getPost('video_duration_seconds');

            $data['youtube_url']             = $youtubeUrl;
            $data['video_duration_seconds']  = $duration;
            $data['display_duration_seconds'] = $duration;
        }

        $this->contentModel->update($id, $data);
        $this->notifyTvsByCategory($categoryId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Konten video berhasil diperbarui.'
        ]);
    }

    /**
     * Endpoint AJAX: Update Konten Chart
     */
    public function updateChart($id = null)
    {
        $content = $this->contentModel->find($id);

        if (!$content) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Konten tidak ditemukan.'
            ]);
        }

        if (!$this->checkCategoryPermission((int) $content['category_id'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke konten kategori ini.'
            ]);
        }

        $rules = [
            'title'                    => 'required|min_length[2]|max_length[150]',
            'category_id'              => 'required|numeric',
            'chart_type'               => 'required|in_list[bar,line,pie]',
            'display_duration_seconds' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $categoryId = (int) $this->request->getPost('category_id');

        if (!$this->checkCategoryPermission($categoryId)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => ['category_id' => 'Anda tidak memiliki akses ke kategori ini.']
            ]);
        }

        $labels = $this->request->getPost('labels') ?? [];
        $values = $this->request->getPost('values') ?? [];

        $cleanLabels = [];
        $cleanValues = [];
        for ($i = 0; $i < count($labels); $i++) {
            $lbl = trim($labels[$i]);
            if ($lbl !== '') {
                $cleanLabels[] = $lbl;
                $cleanValues[] = (float) ($values[$i] ?? 0);
            }
        }

        if (empty($cleanLabels)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => ['labels' => 'Minimal 1 baris label & nilai data chart wajib diisi.']
            ]);
        }

        $data = [
            'category_id'              => $categoryId,
            'title'                    => trim($this->request->getPost('title')),
            'display_duration_seconds' => (int) $this->request->getPost('display_duration_seconds'),
            'is_active'                => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $this->contentModel->update($id, $data);

        // Update Chart JSON Data
        $chartRow       = $this->contentChartModel->getByContentId((int) $id);
        $chartLabelName = trim($this->request->getPost('dataset_label')) ?: 'Data Chart';
        $chartColor     = $this->request->getPost('dataset_color') ?: '#3b82f6';

        $datasets = [
            [
                'label' => $chartLabelName,
                'data'  => $cleanValues,
                'color' => $chartColor,
            ]
        ];

        if ($chartRow) {
            $this->contentChartModel->update($chartRow['id'], [
                'chart_type'     => $this->request->getPost('chart_type'),
                'chart_labels'   => json_encode($cleanLabels),
                'chart_datasets' => json_encode($datasets),
            ]);
        } else {
            $this->contentChartModel->insert([
                'content_id'     => $id,
                'chart_type'     => $this->request->getPost('chart_type'),
                'chart_labels'   => json_encode($cleanLabels),
                'chart_datasets' => json_encode($datasets),
            ]);
        }

        $this->notifyTvsByCategory($categoryId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Konten chart berhasil diperbarui.'
        ]);
    }

    /**
     * Endpoint AJAX: Soft Delete Konten
     */
    public function delete($id = null)
    {
        $content = $this->contentModel->find($id);

        if (!$content) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Konten tidak ditemukan.'
            ]);
        }

        if (!$this->checkCategoryPermission((int) $content['category_id'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke konten kategori ini.'
            ]);
        }

        $categoryId = (int) $content['category_id'];

        // Audit Log: Hapus konten
        AuditLogger::log('delete', 'content', "Menghapus konten: {$content['title']} (Tipe: {$content['type']})", [
            'entity_id'   => $id,
            'entity_name' => $content['title'],
            'old_values'  => ['title' => $content['title'], 'type' => $content['type'], 'category_id' => $content['category_id']],
        ]);

        $this->contentModel->delete($id);

        $this->notifyTvsByCategory($categoryId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Konten berhasil dihapus.'
        ]);
    }

    /**
     * Endpoint AJAX: Toggle status aktif/nonaktif
     */
    public function toggleStatus($id = null)
    {
        $content = $this->contentModel->find($id);

        if (!$content) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Konten tidak ditemukan.'
            ]);
        }

        if (!$this->checkCategoryPermission((int) $content['category_id'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke konten kategori ini.'
            ]);
        }

        $newStatus  = $content['is_active'] == 1 ? 0 : 1;
        $categoryId = (int) $content['category_id'];

        $this->contentModel->update($id, ['is_active' => $newStatus]);
        $this->notifyTvsByCategory($categoryId);

        // Audit Log: Toggle status konten
        $statusLabel = $newStatus === 1 ? 'Aktif' : 'Nonaktif';
        AuditLogger::log('toggle', 'content', "Mengubah status konten: {$content['title']} menjadi {$statusLabel}", [
            'entity_id'   => $id,
            'entity_name' => $content['title'],
            'old_values'  => ['is_active' => $content['is_active']],
            'new_values'  => ['is_active' => $newStatus],
        ]);

        return $this->response->setJSON([
            'status'     => 'success',
            'message'    => 'Status konten berhasil diperbarui.',
            'new_status' => $newStatus
        ]);
    }

    /**
     * Helper: Extract YouTube Video ID from URL
     */
    protected function extractYoutubeId(string $url): ?string
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Helper: Trigger SSE event to TVs registered under category
     */
    protected function notifyTvsByCategory(int $categoryId)
    {
        $db = \Config\Database::connect();
        $tvs = $db->table('tv_categories')
                  ->select('tv_id')
                  ->where('category_id', $categoryId)
                  ->get()
                  ->getResultArray();

        foreach ($tvs as $tv) {
            $this->tvEventModel->pushEvent((int) $tv['tv_id'], 'content_changed');
        }
    }

    /**
     * Helper untuk memproses file gambar unggahan: konversi ke WebP & kompresi resolusi
     */
    protected function processAndSaveWebp($file, string $uploadPath): array
    {
        $fileBaseName = pathinfo($file->getRandomName(), PATHINFO_FILENAME) . '.webp';
        $fullPath     = $uploadPath . $fileBaseName;

        $tempName = $file->getRandomName();
        $file->move($uploadPath, $tempName);
        $tempPath = $uploadPath . $tempName;

        $success = false;

        if (function_exists('imagewebp')) {
            $mime   = mime_content_type($tempPath);
            $srcImg = null;

            if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                $srcImg = @imagecreatefromjpeg($tempPath);
            } elseif ($mime === 'image/png') {
                $srcImg = @imagecreatefrompng($tempPath);
                if ($srcImg) {
                    imagepalettetotruecolor($srcImg);
                    imagealphablending($srcImg, true);
                    imagesavealpha($srcImg, true);
                }
            } elseif ($mime === 'image/webp') {
                $srcImg = @imagecreatefromwebp($tempPath);
            }

            if ($srcImg) {
                $width  = imagesx($srcImg);
                $height = imagesy($srcImg);

                if ($width > 1920 || $height > 1080) {
                    $ratio      = min(1920 / $width, 1080 / $height);
                    $newWidth   = (int) round($width * $ratio);
                    $newHeight  = (int) round($height * $ratio);
                    $resizedImg = imagecreatetruecolor($newWidth, $newHeight);
                    imagealphablending($resizedImg, false);
                    imagesavealpha($resizedImg, true);
                    imagecopyresampled($resizedImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagedestroy($srcImg);
                    $srcImg = $resizedImg;
                }

                $success = imagewebp($srcImg, $fullPath, 85);
                imagedestroy($srcImg);
            }
        }

        if (!$success) {
            try {
                $imageService = \Config\Services::image('gd');
                $imageService->withFile($tempPath)
                             ->convert(IMAGETYPE_WEBP)
                             ->save($fullPath, 85);
                $success = file_exists($fullPath);
            } catch (\Throwable $e) {
                $success = false;
            }
        }

        if ($success && file_exists($fullPath)) {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
            return [
                'file_path' => $fileBaseName,
                'file_size' => filesize($fullPath),
            ];
        }

        return [
            'file_path' => $tempName,
            'file_size' => filesize($tempPath),
        ];
    }
}
