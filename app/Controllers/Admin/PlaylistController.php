<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TvModel;
use App\Models\TvPlaylistItemModel;
use App\Models\TvUpdateEventModel;

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
            'title'        => 'Kelola Playlist per TV',
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

        $playlist = $this->playlistItemModel->getPlaylistForTv((int) $tvId);

        foreach ($playlist as &$cnt) {
            if ($cnt['type'] === 'image' && $cnt['file_path']) {
                $cnt['file_url'] = base_url('uploads/contents/images/' . $cnt['file_path']);
            } elseif ($cnt['type'] === 'video') {
                if ($cnt['video_source'] === 'upload' && $cnt['file_path']) {
                    $cnt['file_url'] = base_url('uploads/contents/videos/' . $cnt['file_path']);
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

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Urutan playlist berhasil diperbarui.'
        ]);
    }
}
