<?php

namespace App\Http\Middleware;

use Closure;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;
use Prometheus\Exception\MetricsRegistrationException;

class MetricsMiddleware
{
    protected $registry;

    public function __construct()
    {
        $this->registry = new CollectorRegistry(new InMemory());
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Start timer
        $start = microtime(true);

        // Process the request
        $response = $next($request);

        // Calculate request duration
        $duration = microtime(true) - $start;

        // Register metrics
        $this->registerMetrics($request, $response, $duration);

        return $response;
    }

    protected function registerMetrics($request, $response, $duration)
    {
        try {
            // Register histogram if it doesn't exist
            $this->registry->registerHistogram(
                'app',
                'http_request_duration_seconds',
                'HTTP request duration in seconds',
                ['method', 'endpoint', 'status_code']
            );
        } catch (MetricsRegistrationException $e) {
            // Histogram already registered
        }

        // Get the histogram
        $histogram = $this->registry->getHistogram(
            'app',
            'http_request_duration_seconds'
        );

        // Observe the duration
        $histogram->observe(
            $duration,
            [$request->method(), $request->path(), $response->getStatusCode()]
        );

        try {
            // Register counter if it doesn't exist
            $this->registry->registerCounter(
                'app',
                'http_requests_total',
                'Total number of HTTP requests',
                ['method', 'endpoint', 'status_code']
            );
        } catch (MetricsRegistrationException $e) {
            // Counter already registered
        }

        // Get the counter
        $counter = $this->registry->getCounter(
            'app',
            'http_requests_total'
        );

        // Increment the counter
        $counter->inc([$request->method(), $request->path(), $response->getStatusCode()]);

        // Register site access counter if it doesn't exist
        try {
            $this->registry->registerCounter(
                'app',
                'site_access_total',
                'Total number of site accesses'
            );
        } catch (MetricsRegistrationException $e) {
            // Counter already registered
        }

        // Get the site access counter
        $siteAccessCounter = $this->registry->getCounter(
            'app',
            'site_access_total'
        );

        // Increment the site access counter
        $siteAccessCounter->inc();
    }
}
