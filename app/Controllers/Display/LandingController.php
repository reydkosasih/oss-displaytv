<?php

namespace App\Controllers\Display;

use App\Controllers\BaseController;
use App\Models\TvModel;

class LandingController extends BaseController
{
    protected $tvModel;

    public function __construct()
    {
        $this->tvModel = new TvModel();
    }

    /**
     * Landing Page Publik Card Grid TV Display
     */
    public function index()
    {
        $db  = \Config\Database::connect();
        $tvs = $this->tvModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();

        foreach ($tvs as &$tv) {
            // Get categories
            $tv['categories'] = $db->table('tv_categories tc')
                                  ->select('c.name, c.color')
                                  ->join('categories c', 'c.id = tc.category_id')
                                  ->where('tc.tv_id', $tv['id'])
                                  ->where('c.deleted_at', null)
                                  ->get()
                                  ->getResultArray();

            $tv['thumbnail_url'] = $tv['thumbnail'] 
                ? base_url('uploads/thumbnails/' . $tv['thumbnail'])
                : null;

            // Check online status (seen within 30s)
            $tv['is_online'] = false;
            if ($tv['last_seen_at']) {
                if ((time() - strtotime($tv['last_seen_at'])) <= 30) {
                    $tv['is_online'] = true;
                }
            }
        }

        return view('display/landing', [
            'title' => 'Portal TV Display Slideshow',
            'tvs'   => $tvs,
        ]);
    }

    /**
     * Endpoint AJAX: Verifikasi PIN 6-Digit TV
     */
    public function verifyPin()
    {
        $tvId = (int) $this->request->getPost('tv_id');
        $pin  = trim((string) $this->request->getPost('pin'));

        if (!$tvId || strlen($pin) !== 6) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Silakan masukkan PIN 6-digit dengan lengkap.'
            ]);
        }

        $tv = $this->tvModel->find($tvId);

        if (!$tv || $tv['is_active'] != 1) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Display TV tidak ditemukan atau nonaktif.'
            ]);
        }

        if ($tv['pin'] !== $pin) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'PIN 6-digit yang Anda masukkan salah. Silakan coba lagi.'
            ]);
        }

        // Save access log to tv_access_logs
        $db = \Config\Database::connect();
        $db->table('tv_access_logs')->insert([
            'tv_id'       => $tvId,
            'ip_address'  => $this->request->getIPAddress(),
            'user_agent'  => (string) $this->request->getUserAgent(),
            'accessed_at' => date('Y-m-d H:i:s'),
        ]);

        // Set verified session token
        $sessionKey = 'tv_verified_' . $tvId;
        session()->set($sessionKey, [
            'tv_id'       => $tvId,
            'slug'        => $tv['slug'],
            'verified_at' => time(),
        ]);

        return $this->response->setJSON([
            'status'       => 'success',
            'message'      => 'PIN Berhasil Diverifikasi!',
            'redirect_url' => base_url('display/' . $tv['slug'])
        ]);
    }
}
