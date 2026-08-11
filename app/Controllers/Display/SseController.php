<?php

namespace App\Controllers\Display;

use App\Controllers\BaseController;
use App\Models\TvModel;
use App\Models\TvUpdateEventModel;
use App\Libraries\SseStreamer;

class SseController extends BaseController
{
    protected $tvModel;
    protected $tvEventModel;

    public function __construct()
    {
        $this->tvModel      = new TvModel();
        $this->tvEventModel = new TvUpdateEventModel();
    }

    /**
     * SSE Event Stream Endpoint for TV Display (/display/{slug}/events)
     */
    public function events($slug = null)
    {
        // Immediately release session lock so other HTTP requests (images, reloads) don't hang
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        $tv = $this->tvModel->where('slug', $slug)->where('is_active', 1)->first();

        if (!$tv) {
            header("HTTP/1.1 404 Not Found");
            echo "TV not found";
            exit;
        }

        $tvId = (int) $tv['id'];
        $sse  = new SseStreamer();

        // Send initial connection event
        $sse->sendEvent('connected', [
            'tv_id'     => $tvId,
            'tv_name'   => $tv['name'],
            'timestamp' => time(),
        ]);

        $db = \Config\Database::connect();

        // Update last_seen_at in tvs table (TV Online status monitoring)
        $db->table('tvs')->where('id', $tvId)->update([
            'last_seen_at' => date('Y-m-d H:i:s'),
        ]);

        // Get starting last event ID
        $lastEventRow = $db->table('tv_update_events')
                           ->selectMax('id', 'max_id')
                           ->where('tv_id', $tvId)
                           ->get()
                           ->getRow();

        $lastEventId = $lastEventRow ? (int) $lastEventRow->max_id : 0;
        $iterationCounter = 0;

        // Loop for max 5 iterations (10 seconds) per connection
        // This prevents single-threaded dev servers (php spark serve / php -S) from hanging
        // Browser EventSource automatically reconnects 1-2 seconds after connection ends
        while ($iterationCounter < 5) {
            if (connection_aborted()) {
                break;
            }

            // Query new events since lastEventId
            $newEvents = $db->table('tv_update_events')
                            ->where('tv_id', $tvId)
                            ->where('id >', $lastEventId)
                            ->orderBy('id', 'ASC')
                            ->get()
                            ->getResultArray();

            if (!empty($newEvents)) {
                foreach ($newEvents as $evt) {
                    $lastEventId = (int) $evt['id'];
                    $sse->sendEvent($evt['event_type'], [
                        'tv_id'      => $tvId,
                        'event_type' => $evt['event_type'],
                        'event_id'   => $evt['id'],
                        'timestamp'  => time(),
                    ], $evt['id']);
                }
            }

            $iterationCounter++;
            sleep(2);
        }

        exit;
    }
}
