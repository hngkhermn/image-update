<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

class MetricsController extends Controller
{
    public function metrics()
    {
        $averageResponseTime = Cache::get('average_response_time', 0);
        $errorCount = Cache::get('error_count', 0);
        $totalRequests = Cache::get('total_requests', 1); // Hindari pembagian dengan 0
        $errorRate = ($errorCount / $totalRequests) * 100;
        $successfulRequests = Cache::get('successful_requests', 0);
        $successRate = ($successfulRequests / $totalRequests) * 100;
        $totalDowntime = Cache::get('total_downtime', 0);
        $totalMinutesInMonth = 43200;
        $uptime = ((1 - ($totalDowntime / $totalMinutesInMonth)) * 100);
        $averageMemoryUsage = Cache::get('average_memory_usage', 0);

        $metrics = <<<EOT
        # HELP app_average_response_time Average response time in milliseconds.
        # TYPE app_average_response_time gauge
        app_average_response_time {$averageResponseTime}

        # HELP app_error_rate Percentage of requests that result in errors.
        # TYPE app_error_rate gauge
        app_error_rate {$errorRate}

        # HELP app_success_rate Percentage of successful requests.
        # TYPE app_success_rate gauge
        app_success_rate {$successRate}

        # HELP app_uptime Uptime percentage of the application.
        # TYPE app_uptime gauge
        app_uptime {$uptime}

        # HELP app_average_memory_usage Average memory usage in MB.
        # TYPE app_average_memory_usage gauge
        app_average_memory_usage {$averageMemoryUsage}

        # HELP app_total_requests Total number of requests received.
        # TYPE app_total_requests counter
        app_total_requests {$totalRequests}
        EOT;

        return response($metrics, 200)
                ->header('Content-Type', 'text/plain');
    }
}
