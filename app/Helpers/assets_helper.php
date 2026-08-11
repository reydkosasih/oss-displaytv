<?php

if (! function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        $filePath = FCPATH . ltrim($path, '/');
        $version  = is_file($filePath) ? filemtime($filePath) : time();

        return base_url($path) . '?v=' . $version;
    }
}
