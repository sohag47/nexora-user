<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class UserService
{
    /**
     * Get a filtered, sorted, and paginated list of users.
     *
     * @param  array  $filters  Validated query parameters from GetUsersRequest
     */
    public function getPaginated(array $filters): LengthAwarePaginator
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

    public function findUserById(int $id): ?User
    {
        return User::with(['branch', 'designation', 'department'])->find($id);
    }

    public function createUser(array $request)
    {
        $data['name'] = $request['name'];
        $data['username'] = $request['username'];
        $data['email'] = $request['email'];
        $data['phone'] = $request['phone'];
        $data['gender'] = $request['gender'];
        $data['password'] = $request['password'];
        $data['branch_id'] = $request['branch_id'];
        $data['designation_id'] = $request['designation_id'];
        $data['department_id'] = $request['department_id'];

        return User::create($data);
    }

    public function updateUser(User $user, array $data)
    {
        return $user->update($data);
    }

    public function deleteUser(User $model): bool
    {
        return $model->delete();
    }

    public function dropdown(Request $request)
    {

        $search = trim($filters['search'] ?? '');
        $status = $filters['status'] ?? null;

        return User::newQuery()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when(! empty($status), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id as value', 'name as label', 'status']);
        // $query = User::query();

        // if ($request->filled('search')) {
        //     $query->where('name', 'LIKE', '%'.$request->query('search').'%');
        // }

        // return $query->select('id', 'name', 'status')
        //     ->get()
        //     ->map(fn ($user) => [
        //         'value' => $user->id,
        //         'label' => $user->name,
        //         'status' => $user->status,
        //     ]);
    }
}
