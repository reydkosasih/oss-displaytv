<?php

namespace App\Controllers\Display;

use App\Controllers\BaseController;
use App\Models\TvModel;
use App\Models\TvPlaylistItemModel;

class DisplayController extends BaseController
{
    protected $tvModel;
    protected $playlistItemModel;

    public function __construct()
    {
        $this->tvModel           = new TvModel();
        $this->playlistItemModel = new TvPlaylistItemModel();
    }

    /**
     * Halaman Fullscreen TV Slideshow Display
     */
    public function index($slug = null)
    {
        if (!$slug) {
            return redirect()->to('/');
        }

        $tv = $this->tvModel->where('slug', $slug)->where('is_active', 1)->first();

        if (!$tv) {
            session()->setFlashdata('error', 'Display TV tidak ditemukan.');
            return redirect()->to('/');
        }

        $playlist = $this->getPlaylistData((int) $tv['id']);

        return view('display/slideshow', [
            'title'           => esc($tv['name']) . ' — TV Display',
            'tv'              => $tv,
            'initialPlaylist' => $playlist,
        ]);
    }

    /**
     * Endpoint AJAX: Ambil playlist JSON terurut untuk TV tertentu
     */
    public function getPlaylistJson($slug = null)
    {
        $tv = $this->tvModel->where('slug', $slug)->where('is_active', 1)->first();

        if (!$tv) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'TV tidak ditemukan.'
            ]);
        }

        $playlist = $this->getPlaylistData((int) $tv['id']);

        return $this->response->setJSON([
            'status'   => 'success',
            'tv'       => [
                'id'             => $tv['id'],
                'name'           => $tv['name'],
                'slug'           => $tv['slug'],
                'location'       => $tv['location'],
                'pin_updated_at' => $tv['pin_updated_at'],
            ],
            'playlist' => $playlist
        ]);
    }

    /**
     * Helper: Ambil & format data playlist untuk TV tertentu
     */
    protected function getPlaylistData(int $tvId): array
    {
        $playlist = $this->playlistItemModel->getPlaylistForTv($tvId, true);
        $db       = \Config\Database::connect();

        foreach ($playlist as &$cnt) {
            if ($cnt['type'] === 'image' && $cnt['file_path']) {
                $cnt['file_url'] = base_url('uploads/contents/images/' . $cnt['file_path']);
            } elseif ($cnt['type'] === 'video') {
                if ($cnt['video_source'] === 'upload' && $cnt['file_path']) {
                    $cnt['file_url'] = base_url('uploads/contents/videos/' . $cnt['file_path']);
                } elseif ($cnt['video_source'] === 'youtube' && $cnt['youtube_url']) {
                    $cnt['youtube_id'] = $this->extractYoutubeId((string) $cnt['youtube_url']);
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

        return $playlist;
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
}
