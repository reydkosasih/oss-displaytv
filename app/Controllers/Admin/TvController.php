<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TvModel;
use App\Models\CategoryModel;
use App\Models\TvCategoryModel;
use App\Models\TvUpdateEventModel;

class TvController extends BaseController
{
    protected $tvModel;
    protected $categoryModel;
    protected $tvCategoryModel;
    protected $tvEventModel;

    public function __construct()
    {
        $this->tvModel         = new TvModel();
        $this->categoryModel   = new CategoryModel();
        $this->tvCategoryModel = new TvCategoryModel();
        $this->tvEventModel    = new TvUpdateEventModel();
    }

    /**
     * Tampilkan halaman utama Kelola TV
     */
    public function index()
    {
        $categories = $this->categoryModel->orderBy('name', 'ASC')->findAll();

        return view('admin/tv/index', [
            'title'      => 'Kelola Display TV',
            'categories' => $categories,
        ]);
    }

    /**
     * Endpoint AJAX: List data TV (JSON) + categories & thumbnail URL
     */
    public function list()
    {
        $db  = \Config\Database::connect();
        $tvs = $this->tvModel->orderBy('created_at', 'DESC')->findAll();

        foreach ($tvs as &$tv) {
            // Get categories
            $tv['categories'] = $db->table('tv_categories tc')
                                  ->select('c.id, c.name, c.color')
                                  ->join('categories c', 'c.id = tc.category_id')
                                  ->where('tc.tv_id', $tv['id'])
                                  ->where('c.deleted_at', null)
                                  ->get()
                                  ->getResultArray();

            $tv['category_ids'] = array_column($tv['categories'], 'id');

            // Thumbnail URL
            $tv['thumbnail_url'] = $tv['thumbnail'] 
                ? base_url('uploads/thumbnails/' . $tv['thumbnail'])
                : null;

            // Online status (last seen within 30 seconds)
            $tv['is_online'] = false;
            if ($tv['last_seen_at']) {
                $lastSeen = strtotime($tv['last_seen_at']);
                if ((time() - $lastSeen) <= 30) {
                    $tv['is_online'] = true;
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $tvs
        ]);
    }

    /**
     * Endpoint AJAX: Simpan TV baru
     */
    public function store()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'location' => 'permit_empty|max_length[150]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $name = trim($this->request->getPost('name'));
        $slug = $this->tvModel->generateSlug($name);
        $pin  = $this->tvModel->generatePin();

        // Handle Thumbnail Upload
        $thumbnailName = null;
        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $thumbnailName = $file->getRandomName();
            $uploadPath = ROOTPATH . 'public/uploads/thumbnails/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $thumbnailName);
        }

        $tvData = [
            'name'           => $name,
            'slug'           => $slug,
            'location'       => $this->request->getPost('location'),
            'thumbnail'      => $thumbnailName,
            'pin'            => $pin,
            'pin_updated_at' => date('Y-m-d H:i:s'),
            'orientation'    => 'landscape',
            'is_active'      => $this->request->getPost('is_active') ? 1 : 0,
            'created_by'     => session()->get('user_id'),
        ];

        $tvId = $this->tvModel->insert($tvData);

        // Sync Categories
        $categoryIds = $this->request->getPost('category_ids') ?? [];
        $this->tvCategoryModel->syncCategories((int) $tvId, (array) $categoryIds);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Display TV baru berhasil ditambahkan.'
        ]);
    }

    /**
     * Endpoint AJAX: Detail TV untuk edit
     */
    public function getJson($id = null)
    {
        $tv = $this->tvModel->find($id);

        if (!$tv) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'TV tidak ditemukan.'
            ]);
        }

        $tv['category_ids']  = $this->tvCategoryModel->getCategoryIdsByTv((int) $id);
        $tv['thumbnail_url'] = $tv['thumbnail'] ? base_url('uploads/thumbnails/' . $tv['thumbnail']) : null;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $tv
        ]);
    }

    /**
     * Endpoint AJAX: Update TV
     */
    public function update($id = null)
    {
        $tv = $this->tvModel->find($id);

        if (!$tv) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'TV tidak ditemukan.'
            ]);
        }

        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'location' => 'permit_empty|max_length[150]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $name = trim($this->request->getPost('name'));
        $slug = ($name !== $tv['name']) 
            ? $this->tvModel->generateSlug($name, (int) $id) 
            : $tv['slug'];

        $tvData = [
            'name'      => $name,
            'slug'      => $slug,
            'location'  => $this->request->getPost('location'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Handle Thumbnail Upload if provided
        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $thumbnailName = $file->getRandomName();
            $uploadPath = ROOTPATH . 'public/uploads/thumbnails/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $thumbnailName);
            $tvData['thumbnail'] = $thumbnailName;
        }

        $this->tvModel->update($id, $tvData);

        // Sync Categories
        $categoryIds = $this->request->getPost('category_ids') ?? [];
        $this->tvCategoryModel->syncCategories((int) $id, (array) $categoryIds);

        // Push SSE event category/playlist update
        $this->tvEventModel->pushEvent((int) $id, 'playlist_changed');

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Display TV berhasil diperbarui.'
        ]);
    }

    /**
     * Endpoint AJAX: Soft Delete TV
     */
    public function delete($id = null)
    {
        $tv = $this->tvModel->find($id);

        if (!$tv) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'TV tidak ditemukan.'
            ]);
        }

        $this->tvModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Display TV berhasil dihapus.'
        ]);
    }

    /**
     * Endpoint AJAX: Regenerate 6-Digit PIN
     */
    public function regeneratePin($id = null)
    {
        $tv = $this->tvModel->find($id);

        if (!$tv) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'TV tidak ditemukan.'
            ]);
        }

        $newPin = $this->tvModel->generatePin();

        $this->tvModel->update($id, [
            'pin'            => $newPin,
            'pin_updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Push event 'pin_regenerated' to tv_update_events for SSE trigger
        $this->tvEventModel->pushEvent((int) $id, 'pin_regenerated');

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'PIN TV berhasil diperbarui.',
            'new_pin' => $newPin
        ]);
    }
}
