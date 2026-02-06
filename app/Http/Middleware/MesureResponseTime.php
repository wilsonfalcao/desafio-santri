<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MesureResponseTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $start = microtime(true);

        $response = $next($request);

        $duration = number_format((microtime(true) - $start) * 1000, 2);

        $data = $response->getData(true);

        $data['performance'] = [
            'duration_ms' => $duration,
            'memory_usage_mb' => round(memory_get_usage() / 1024 / 1024, 2),
        ];

        $response->setData($data);

        return $response;
    }
}
