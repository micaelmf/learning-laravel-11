<?php

namespace App\Http\Controllers;

use App\Services\Prometheus\PrometheusService;
use Illuminate\Http\JsonResponse;
use Prometheus\Exception\MetricsRegistrationException;

class PrometheusController extends Controller
{
    private PrometheusService $service;

    public function __construct(PrometheusService $service)
    {
        $this->service = $service;
    }

    public function metrics(): string
    {
        return $this->service->metrics();
    }

    /**
     * @throws MetricsRegistrationException
     */
    public function createTestOrder(): JsonResponse
    {
        $this->service->createTestOrder();

        // Create Order Codes

        return response()->json(['message' => 'Order created successfully']);
    }
}
