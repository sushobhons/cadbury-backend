<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class InstagramHelper
{
    /**
     * Check if Instagram username exists
     */
    public static function exists(string $username): bool
    {
        $username = ltrim($username, '@');
        $url = "https://www.instagram.com/" . $username . "/";

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
            ])->get($url);

            return $response->status() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }
}
