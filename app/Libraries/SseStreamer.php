<?php

namespace App\Libraries;

class SseStreamer
{
    public function __construct()
    {
        // Disable time limit
        if (function_exists('set_time_limit')) {
            @set_time_limit(0);
        }

        // Send headers
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache, no-transform');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Disable Nginx buffering

        // Clear existing buffers
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
        ob_implicit_flush(true);
    }

    /**
     * Send formatted SSE event block
     */
    public function sendEvent(string $event, $data = [], $id = null)
    {
        if ($id !== null) {
            echo "id: {$id}\n";
        }
        echo "event: {$event}\n";
        echo "data: " . json_encode($data) . "\n\n";

        if (ob_get_level() > 0) {
            @ob_flush();
        }
        @flush();
    }
}
