<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkVisit;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'url' => ['required', 'url'],
        ]);

        $shortCode = $this->generateShortCode();

        $link = Link::create([
            'original_url' => $request->url,
            'short_code' => $shortCode,
        ]);

        return response()->json([
            'short_url' => url($link->short_code),
        ]);
    }
    public function redirect(string $code, Request $request)
    {
        $link = Link::where('short_code', $code)->firstOrFail();

        $link->visits()->create([
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->away($link->original_url);
    }
    private function generateShortCode(int $length = 6): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (Link::where('short_code', $code)->exists());

        return $code;
    }


}
