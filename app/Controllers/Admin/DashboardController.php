<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        // Cache halaman selama 60 detik untuk mengurangi beban 5x DB queries per-request.
        // Cache otomatis expire setelah 60 detik & rebuild dari DB.
        // Hanya berlaku untuk request GET tanpa query params (CI4 cachePage default behavior).
        $this->cachePage(60);

        $db = \Config\Database::connect();

        $stats = [
            'total_tvs'        => $db->table('tvs')->where('deleted_at', null)->countAllResults(),
            'active_tvs'       => $db->table('tvs')->where('deleted_at', null)->where('is_active', 1)->countAllResults(),
            'total_categories' => $db->table('categories')->where('deleted_at', null)->countAllResults(),
            'total_contents'   => $db->table('contents')->where('deleted_at', null)->countAllResults(),
            'total_users'      => $db->table('users')->where('deleted_at', null)->countAllResults(),
        ];

        // Online TVs (last_seen_at within 30 seconds)
        $onlineThreshold = date('Y-m-d H:i:s', time() - 30);
        $onlineTvs = $db->table('tvs')
                        ->where('deleted_at', null)
                        ->where('is_active', 1)
                        ->where('last_seen_at >=', $onlineThreshold)
                        ->get()
                        ->getResultArray();

        $stats['online_tvs'] = count($onlineTvs);

        // Content distribution per type
        $contentTypes = $db->table('contents')
                           ->select('type, COUNT(*) as total')
                           ->where('deleted_at', null)
                           ->groupBy('type')
                           ->get()
                           ->getResultArray();

        // Content distribution per category
        $contentByCategory = $db->table('contents c')
                                ->select('cat.name, cat.color, COUNT(c.id) as total')
                                ->join('categories cat', 'cat.id = c.category_id')
                                ->where('c.deleted_at', null)
                                ->groupBy('c.category_id')
                                ->orderBy('total', 'DESC')
                                ->limit(8)
                                ->get()
                                ->getResultArray();

        // Recent TVs list with online status
        $recentTvs = $db->table('tvs')
                        ->where('deleted_at', null)
                        ->orderBy('created_at', 'DESC')
                        ->limit(8)
                        ->get()
                        ->getResultArray();

        // Attach online status to each TV
        $onlineTvIds = array_column($onlineTvs, 'id');
        foreach ($recentTvs as &$tv) {
            $tv['is_online'] = in_array($tv['id'], $onlineTvIds);
        }

        // Recent PIN Access Logs
        $recentAccessLogs = $db->table('tv_access_logs al')
                               ->select('al.*, tv.name as tv_name, tv.location as tv_location')
                               ->join('tvs tv', 'tv.id = al.tv_id')
                               ->orderBy('al.accessed_at', 'DESC')
                               ->limit(10)
                               ->get()
                               ->getResultArray();

        return view('admin/dashboard/index', [
            'title'              => 'Dashboard Overview',
            'stats'              => $stats,
            'recentTvs'          => $recentTvs,
            'contentTypes'       => $contentTypes,
            'contentByCategory'  => $contentByCategory,
            'recentAccessLogs'   => $recentAccessLogs,
        ]);
    }
}
