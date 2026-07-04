<?php

namespace App\Services;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchService
{
    private $model;

    public function __construct(Branch $model)
    {
        $this->model = $model;
    }

    /**
     * Get a filtered, sorted, and paginated list of users.
     *
     * @param  array  $filters  Validated query parameters from GetUsersRequest
     */
    public function getPaginated(array $filters)
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

    public function findById(int $id)
    {
        return $this->model::find($id);
    }

    public function createItem(array $request)
    {
        $data['name'] = $request['name'];
        $data['code'] = $request['code'];
        $data['address'] = $request['address'];

        return $this->model::create($data);
    }

    public function updateItem(Branch $model, array $data)
    {
        return $model->update($data);
    }

    public function deleteItem(Branch $model): bool
    {
        return $model->delete();
    }

    public function dropdown(Request $request)
    {
        $query = $this->model::query();

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%'.$request->query('search').'%');
        }

        return $query->select('id', 'name', 'status')
            ->limit(20)
            ->get()
            ->map(fn ($user) => [
                'value' => $user->id,
                'label' => $user->name,
                'status' => $user->status,
            ]);
    }
}
