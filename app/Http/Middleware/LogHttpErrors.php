<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LogHttpErrors
{
        /* 
    Handle an incoming request.*
    @param  \Illuminate\Http\Request  $request
    @param  \Closure  $next
    @return mixed*/

    public function handle($request, Closure $next){ 
	// Proses permintaan
	$response = $next($request);

            // Cek jika status code adalah 500 atau jenis error lain
    	if ($response->status() >= 500) {
        	$this->incrementErrorCount();
                Log::error("HTTP Error: {$response->status()} on " . $request->fullUrl());
    	}
        return $response;
    }

        /*Tambahkan error count dalam cache*/
    protected function incrementErrorCount(){
    	$errorCount = Cache::get('error_count', 0) + 1;
        Cache::put('error_count', $errorCount);
    }
}
