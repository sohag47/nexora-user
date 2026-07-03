<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * Get a filtered, sorted, and paginated list of users.
     *
     * @param  array  $filters  Validated query parameters from GetUsersRequest
     */
    public function getPaginatedUsers(array $filters): LengthAwarePaginator
    {
        // 1. Extract dynamic structural values with safe fallbacks
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';
        $perPage = $filters['size'] ?? 10;

        // 2. Build the query chain with eager-loaded relationships
        return User::query()
            ->with(['branch', 'designation', 'department'])

            // Apply Status Filter
            ->when(! empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })

            // Apply Global Search across fields and relationship constraints
            ->when(! empty($filters['search']), function ($query) use ($filters) {
                $searchTerm = '%'.$filters['search'].'%';
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm)
                        ->orWhereHas('branch', function ($branchQuery) use ($searchTerm) {
                            $branchQuery->where('name', 'like', $searchTerm);
                        });
                });
            })

            // Apply sorting rules and fetch pagination chunk
            ->orderBy($sortBy, $direction)
            ->paginate($perPage);
    }
}
