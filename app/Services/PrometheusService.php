<?php

namespace App\Services\Prometheus;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;

class PrometheusService
{
    private CollectorRegistry $collectorRegistry;
    private string $orderCategory = 'default_category'; // Define the orderCategory property

    public function __construct(CollectorRegistry $registry)
    {
        $this->collectorRegistry = $registry->getDefault();
    }

    public function metrics(): string
    {
        $renderer = new RenderTextFormat();
        $result = $renderer->render($this->collectorRegistry->getMetricFamilySamples());
        header('Content-type: ' . RenderTextFormat::MIME_TYPE);

        return $result;
    }

    /**
     * @throws MetricsRegistrationException
     */
    public function createTestOrder($count = 1): void
    {
        $counter = $this->collectorRegistry->registerCounter('orders', 'count', 'Number of Orders', ['category']);

        $counter->incBy($count, [$this->orderCategory]);
    }
}
