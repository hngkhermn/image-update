<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LogDowntime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
   public function handle($request, Closure $next)
    {
        try {
            $response = $next($request);
            $this->checkAndLogUptime();
            return $response;
        } catch (\Exception $e) {
            $this->startDowntime();
            throw $e;
        }
    }

    protected function startDowntime()
    {
        // Jika belum tercatat, simpan waktu mulai downtime
        if (!Cache::has('downtime_start')) {
            Cache::put('downtime_start', now());
            Log::warning('Downtime started at ' . now());
        }
    }

    protected function checkAndLogUptime()
    {
        // Jika sedang downtime, akhiri dan log downtime
        if (Cache::has('downtime_start'))
        {
            $downtimeStart = Cache::get('downtime_start');
            $downtimeDuration = now()->diffInMinutes($downtimeStart);

            // Simpan total downtime
            $totalDowntime = Cache::get('total_downtime', 0) + $downtimeDuration;
            Cache::put('total_downtime', $totalDowntime);

            // Hapus downtime_start dari cache
            Cache::forget('downtime_start');
            Log::info('Downtime ended at ' . now() . ' with duration ' . $downtimeDuration . ' minutes');
        }
    }}
