<?php

namespace App\Services;

use App\Models\Designation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DesignationService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly Designation $model
    ) {}

    /**
     * Get a filtered, sorted, and paginated list of users.
     *
     * @param  array  $filters  Validated query parameters from GetUsersRequest
     */
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';
        $perPage = $filters['size'] ?? 10;

        return $this->model::query()
            ->when(! empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(! empty($filters['search']), function ($query) use ($filters) {
                $searchTerm = '%'.$filters['search'].'%';
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'like', $searchTerm)
                        ->orWhere('code', 'like', $searchTerm)
                        ->orWhere('address', 'like', $searchTerm);
                });
            })
            ->orderBy($sortBy, $direction)
            ->paginate($perPage);
    }

    public function create(array $request): Designation
    {
        return $this->model::create($request);
    }

    public function update(Designation $model, array $data): bool
    {
        return $model->update($data);
    }

    public function delete(Designation $model): ?bool
    {
        return $model->delete();
    }

    public function dropdown(array $filters): Collection
    {
        $search = trim($filters['search'] ?? '');
        $status = $filters['status'] ?? null;

        return $this->model
            ->newQuery()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when(! empty($status), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id as value', 'name as label', 'status']);
    }
}
