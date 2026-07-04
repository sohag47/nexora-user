<?php

namespace App\Http\Controllers;

use App\Http\Requests\Branch\BranchQueyRequest;
use App\Http\Requests\Branch\UpsertBranchRequest;
use App\Http\Requests\DropdownRequest;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use App\Services\BranchService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly BranchService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(BranchQueyRequest $request): JsonResponse
    {
        $collections = $this->service->getPaginated($request->validated());

        $collections->setCollection(BranchResource::collection($collections->getCollection())->collection);

        return $this->success($collections);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpsertBranchRequest $request): JsonResponse
    {
        $item = $this->service->create($request->validated());

        return $this->created(
            new BranchResource($item)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch): JsonResponse
    {
        $resource = new BranchResource($branch);

        return $this->success($resource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpsertBranchRequest $request, Branch $branch): JsonResponse
    {
        $this->service->update($branch, $request->validated());

        return $this->updated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch): JsonResponse
    {
        $this->service->delete($branch);

        return $this->deleted();
    }

    public function dropdown(DropdownRequest $request): JsonResponse
    {
        $response = $this->service->dropdown($request->validated());

        return $this->success($response);
    }
}
