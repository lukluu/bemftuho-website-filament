<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

abstract class Controller
{
    public function handle($request, Closure $next)
    {
        if (!app()->runningInConsole()) {
            // $ip = $request->ip();
            // $now = now();

            // // Simpan data kunjungan di cache
            // $visits = Cache::get('website_visits', []);

            // $key = $now->format('Y-m-d');
            // if (!isset($visits[$key])) {
            //     $visits[$key] = [];
            // }

            // if (!in_array($ip, $visits[$key])) {
            //     $visits[$key][] = $ip;
            // }

            // Cache::put('website_visits', $visits, now()->addMonths(3));
            $ip = $request->ip();
            $date = now()->toDateString();

            // Simpan ke file storage/logs/visitors.log
            $log = "{$date}|{$ip}\n";
            Storage::append('logs/visitors.log', $log);
        }

        return $next($request);
    }
}
