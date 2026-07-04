<?php

namespace App\Http\Controllers;

use App\Http\Requests\Branch\BranchQueyRequest;
use App\Http\Requests\Branch\UpsertBranchRequest;
use App\Http\Resources\BranchResource;
use App\Services\BranchService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    use ApiResponse;

    private $service;

    public function __construct(BranchService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(BranchQueyRequest $request)
    {
        $collections = $this->service->getPaginated($request->validated());

        /** @var LengthAwarePaginator $collections */
        $collections->setCollection(BranchResource::collection($collections->getCollection())->collection);

        return $this->success($collections);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpsertBranchRequest $request)
    {
        $user = $this->service->createItem($request->validated());

        return $this->created($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->service->findById($id);
        if (empty($user)) {
            return $this->notFound();
        }

        $resource = new BranchResource($user);

        return $this->success($resource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpsertBranchRequest $request, string $id)
    {
        $item = $this->service->findById($id);
        if (empty($item)) {
            return $this->notFound();
        }
        $this->service->updateItem($item, $request->validated());

        return $this->updated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = $this->service->findById($id);
        if (empty($item)) {
            return $this->notFound();
        }
        $this->service->deleteItem($item);

        return $this->deleted();
    }

    public function dropdown(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $resource = $this->service->dropdown($request);

        return $this->success($resource);
    }
}
