<?php

namespace App\Http\Controllers;

use App\Http\Requests\Designation\QueyRequest;
use App\Http\Requests\Designation\UpsertRequest;
use App\Http\Requests\DropdownRequest;
use App\Models\Designation;
use App\Services\DesignationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DesignationsController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly DesignationService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(QueyRequest $request): JsonResponse
    {
        $collections = $this->service->getPaginated($request->validated());

        return $this->success($collections);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpsertRequest $request)
    {
        $item = $this->service->create($request->validated());

        return $this->created($item);
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        return $this->success($designation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpsertRequest $request, Designation $designation)
    {
        $this->service->update($designation, $request->validated());

        return $this->updated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation): JsonResponse
    {
        $this->service->delete($designation);

        return $this->deleted();
    }

    public function dropdown(DropdownRequest $request): JsonResponse
    {
        $response = $this->service->dropdown($request->validated());

        return $this->success($response);
    }
}
