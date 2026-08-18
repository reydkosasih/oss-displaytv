<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TvModel;
use App\Models\TvPlaylistItemModel;
use App\Models\TvUpdateEventModel;
use App\Libraries\AuditLogger;

class PlaylistController extends BaseController
{
    protected $tvModel;
    protected $playlistItemModel;
    protected $tvEventModel;

    public function __construct()
    {
        $this->tvModel           = new TvModel();
        $this->playlistItemModel = new TvPlaylistItemModel();
        $this->tvEventModel      = new TvUpdateEventModel();
    }

    /**
     * Halaman Kelola Playlist per TV
     */
    public function index($selectedTvId = null)
    {
        $tvs = $this->tvModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        return view('admin/playlist/index', [
            'title'        => 'Kelola Playlist & Jadwal per TV',
            'tvs'          => $tvs,
            'selectedTvId' => $selectedTvId ?: ($tvs[0]['id'] ?? null),
        ]);
    }

    /**
     * Endpoint AJAX: Ambil daftar slide playlist untuk TV tertentu (JSON)
     */
    public function getPlaylistJson($tvId = null)
    {
        if (!$tvId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'ID TV tidak ditentukan.'
            ]);
        }

        $playlist = $this->playlistItemModel->getPlaylistForTv((int) $tvId, false);
        $db       = \Config\Database::connect();

        foreach ($playlist as &$cnt) {
            if ($cnt['type'] === 'image' && $cnt['file_path']) {
                $cnt['file_url'] = base_url('uploads/contents/images/' . $cnt['file_path']);
            } elseif ($cnt['type'] === 'video') {
                if ($cnt['video_source'] === 'upload' && $cnt['file_path']) {
                    $cnt['file_url'] = base_url('uploads/contents/videos/' . $cnt['file_path']);
                }
            } elseif ($cnt['type'] === 'chart') {
                $chartRow = $db->table('content_charts')->where('content_id', $cnt['id'])->get()->getRowArray();
                if ($chartRow) {
                    $cnt['chart_type']     = $chartRow['chart_type'];
                    $cnt['chart_labels']   = json_decode($chartRow['chart_labels'], true);
                    $cnt['chart_datasets'] = json_decode($chartRow['chart_datasets'], true);
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $playlist
        ]);
    }

    /**
     * Endpoint AJAX: Simpan urutan baru playlist (Drag & Drop)
     */
    public function reorder()
    {
        $tvId       = (int) $this->request->getPost('tv_id');
        $contentIds = $this->request->getPost('content_ids') ?? [];

        if (!$tvId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'TV ID tidak valid.'
            ]);
        }

        // Update sort order
        $this->playlistItemModel->updateSortOrder($tvId, (array) $contentIds);

        // Trigger SSE event playlist_changed
        $this->tvEventModel->pushEvent($tvId, 'playlist_changed');

        // Audit Log: Reorder playlist
        $tv = $this->tvModel->find($tvId);
        AuditLogger::log('reorder', 'playlist', "Mengatur ulang urutan playlist TV: " . ($tv['name'] ?? "ID:{$tvId}"), [
            'entity_id'   => $tvId,
            'entity_name' => $tv['name'] ?? "TV ID:{$tvId}",
            'new_values'  => ['content_order' => array_values((array) $contentIds)],
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Urutan playlist berhasil diperbarui.'
        ]);
    }

    /**
     * Endpoint AJAX: Perbarui jadwal & recurrence satu slide playlist
     */
    public function updateSchedule()
    {
        $tvId        = (int) $this->request->getPost('tv_id');
        $contentId   = (int) $this->request->getPost('content_id');
        $isScheduled = (int) $this->request->getPost('is_scheduled');

        if (!$tvId || !$contentId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Parameter TV dan Konten wajib diisi.'
            ]);
        }

        $startDate  = $this->request->getPost('start_date') ?: null;
        $endDate    = $this->request->getPost('end_date') ?: null;
        $daysOfWeek = $this->request->getPost('days_of_week') ?: [];
        $startTime  = $this->request->getPost('start_time') ?: null;
        $endTime    = $this->request->getPost('end_time') ?: null;

        // Validation for date range
        if ($startDate && $endDate && $startDate > $endDate) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.'
            ]);
        }

        // Determine schedule type
        $scheduleType = 'always';
        if ($isScheduled) {
            if ($startDate || $endDate) {
                $scheduleType = (!empty($daysOfWeek) || $startTime) ? 'custom_range' : 'date_range';
            } elseif (!empty($daysOfWeek)) {
                $scheduleType = 'recurring_weekly';
            } else {
                $scheduleType = 'custom_range';
            }
        }

        $scheduleData = [
            'is_scheduled'  => $isScheduled ? 1 : 0,
            'schedule_type' => $scheduleType,
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'days_of_week'  => !empty($daysOfWeek) ? (array) $daysOfWeek : null,
            'start_time'    => $startTime,
            'end_time'      => $endTime,
        ];

        $this->playlistItemModel->updateItemSchedule($tvId, $contentId, $scheduleData);

        // Trigger SSE
        $this->tvEventModel->pushEvent($tvId, 'playlist_changed');

        // Audit Log: Update jadwal slide playlist
        $tv = $this->tvModel->find($tvId);
        AuditLogger::log('update', 'playlist', "Memperbarui jadwal slide (Content ID:{$contentId}) pada TV: " . ($tv['name'] ?? "ID:{$tvId}"), [
            'entity_id'   => "{$tvId}:{$contentId}",
            'entity_name' => $tv['name'] ?? "TV ID:{$tvId}",
            'new_values'  => $scheduleData,
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Jadwal slide berhasil disimpan.'
        ]);
    }

    /**
     * Endpoint AJAX: Reset jadwal slide ke 'Selalu Tayang'
     */
    public function resetSchedule()
    {
        $tvId      = (int) $this->request->getPost('tv_id');
        $contentId = (int) $this->request->getPost('content_id');

        if (!$tvId || !$contentId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Parameter TV dan Konten wajib diisi.'
            ]);
        }

        $this->playlistItemModel->resetItemSchedule($tvId, $contentId);

        // Trigger SSE
        $this->tvEventModel->pushEvent($tvId, 'playlist_changed');

        // Audit Log: Reset jadwal slide
        $tv = $this->tvModel->find($tvId);
        AuditLogger::log('update', 'playlist', "Reset jadwal slide (Content ID:{$contentId}) ke 'Selalu Tayang' pada TV: " . ($tv['name'] ?? "ID:{$tvId}"), [
            'entity_id'   => "{$tvId}:{$contentId}",
            'entity_name' => $tv['name'] ?? "TV ID:{$tvId}",
            'new_values'  => ['schedule_type' => 'always', 'is_scheduled' => 0],
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Jadwal slide berhasil direset ke Selalu Tayang.'
        ]);
    }

    /**
     * Endpoint AJAX: Ambil data kalender bulanan untuk visualisasi jadwal TV
     */
    public function getCalendarData($tvId = null)
    {
        if (!$tvId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'ID TV tidak ditentukan.'
            ]);
        }

        $year  = (int) ($this->request->getGet('year') ?: date('Y'));
        $month = (int) ($this->request->getGet('month') ?: date('n'));

        // Clamp year & month
        if ($year < 2020 || $year > 2050) $year = (int) date('Y');
        if ($month < 1 || $month > 12) $month = (int) date('n');

        $calendarData = $this->playlistItemModel->getMonthlyCalendarSchedule((int) $tvId, $year, $month);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $calendarData
        ]);
    }
}
