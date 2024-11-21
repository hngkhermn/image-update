<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LogResponseTimeAndErrorRate
{
    public function handle($request, Closure $next)
    {
        // Catat waktu mulai
        $startTime = microtime(true);

        try {
            // Proses permintaan dan catat keberhasilan
            $response = $next($request);
            $this->incrementRequestCount();
            if ($response->status() >= 200 && $response->status() < 300) {
                $this->incrementSuccessfulRequests();
            }

            return $response;
        } catch (\Exception $e) {
            $this->incrementErrorCount();
            $this->startDowntime();
            throw $e;
        } finally {
            $endTime = microtime(true);
            $this->updateResponseMetrics($endTime - $startTime);
            $this->logMemoryUsage();
        }
    }

    protected function incrementRequestCount()
    {
        $requestCount = Cache::get('total_requests', 0) + 1;
        Cache::put('total_requests', $requestCount);
    }

    protected function incrementSuccessfulRequests()
    {
        $successfulRequests = Cache::get('successful_requests', 0) + 1;
        Cache::put('successful_requests', $successfulRequests);
    }
    protected function incrementErrorCount()
    {
        $errorCount = Cache::get('error_count', 0) + 1;
        Cache::put('error_count', $errorCount);
    }

    protected function updateResponseMetrics($responseTime)
    {
        // Average response time under 200ms
        $previousResponseTime = Cache::get('average_response_time', 0);
        $totalRequests = Cache::get('total_requests', 1);

        if ($responseTime * 1000 <= 200) {
            $newAverage = (($previousResponseTime * ($totalRequests - 1)) + $responseTime * 1000) / $totalRequests;
            Cache::put('average_response_time', $newAverage);
        }
    }

    protected function logMemoryUsage()
    {
        $memoryUsage = memory_get_usage() / 1024 / 1024; // Dalam MB
        $previousMemoryUsage = Cache::get('average_memory_usage', 0);
        $totalRequests = Cache::get('total_requests', 1);

        $newAverageMemoryUsage = (($previousMemoryUsage * ($totalRequests - 1)) + $memoryUsage) / $totalRequests;
        Cache::put('average_memory_usage', $newAverageMemoryUsage);
    }
}


