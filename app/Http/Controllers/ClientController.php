<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\QueryRequest;
use App\Http\Requests\Client\UpsertRequest;
use App\Http\Requests\DropdownRequest;
use App\Models\Client;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(QueryRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';
        $perPage = $filters['size'] ?? 10;

        $collections = Client::query()
            ->when(! empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(! empty($filters['search']), function ($query) use ($filters) {
                $searchTerm = '%'.$filters['search'].'%';
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm)
                        ->orWhere('address', 'like', $searchTerm)
                        ->orWhere('phone', 'like', $searchTerm);
                });
            })
            ->orderBy($sortBy, $direction)
            ->paginate($perPage);

        return $this->success($collections);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpsertRequest $request): JsonResponse
    {
        $response = Client::create($request->validated());

        return $this->created($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): JsonResponse
    {
        return $this->success($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpsertRequest $request, Client $client): JsonResponse
    {
        $client->update($request->validated());

        return $this->updated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return $this->deleted();
    }

    public function dropdown(DropdownRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $search = trim($filters['search'] ?? '');
        $status = $filters['status'] ?? null;

        $response = Client::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
                $query->where('email', 'like', "%{$search}%");
                $query->where('phone', 'like', "%{$search}%");
                $query->where('address', 'like', "%{$search}%");
            })
            ->when(! empty($status), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id as value', 'name as label', 'email', 'phone', 'status']);

        return $this->success($response);
    }
}
