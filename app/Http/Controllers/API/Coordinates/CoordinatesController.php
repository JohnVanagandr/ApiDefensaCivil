<?php

namespace App\Http\Controllers\API\Coordinates;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coordinates\StoreCoordinatesRequest;
use App\Http\Requests\Coordinates\UpdateCoordinatesRequest;
use App\Services\Coordinates\CoordinatesService;

class CoordinatesController extends Controller
{
    public function __construct(protected CoordinatesService $service)
    {
    }

    public function index()
    {
        return $this->formatResponse($this->service->getAll());
    }

    public function show(string $id)
    {
        return $this->formatResponse($this->service->getById($id));
    }

    public function getByFamilyPlan(string $familyPlanId)
    {
        return $this->formatResponse($this->service->getByFamilyPlan($familyPlanId));
    }

    public function store(StoreCoordinatesRequest $request)
    {
        return $this->formatResponse($this->service->create($request->validated()));
    }

    public function update(UpdateCoordinatesRequest $request, string $id)
    {
        return $this->formatResponse($this->service->update($request->validated(), $id));
    }

    public function destroy(string $id)
    {
        return $this->formatResponse($this->service->delete($id));
    }

    private function formatResponse(array $response)
    {
        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }
}