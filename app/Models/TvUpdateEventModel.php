<?php

namespace App\Models;

use CodeIgniter\Model;

class TvUpdateEventModel extends Model
{
    protected $table            = 'tv_update_events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tv_id',
        'event_type',
        'created_at',
    ];

    /**
     * Push event trigger for SSE listener
     */
    public function pushEvent(int $tvId, string $eventType)
    {
        return $this->insert([
            'tv_id'      => $tvId,
            'event_type' => $eventType, // 'playlist_changed' | 'content_changed' | 'pin_regenerated'
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
