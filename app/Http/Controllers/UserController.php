<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => ['nullable', 'integer'],
            'size' => ['nullable', 'integer'],
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255', Rule::enum(UserStatus::class)],
            'sort_by' => ['nullable', 'string', Rule::in(['id', 'name', 'email', 'status', 'created_at'])],
            'direction' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        $validated = $validator->validated();

        $query = User::with(['branch', 'designation', 'department']);

        $query->when(! empty($validated['status']), function ($q) use ($validated) {
            $q->where('status', $validated['status']);
        });

        // Apply global search across name, email, or relationships
        $query->when(! empty($validated['search']), function ($q) use ($validated) {
            $searchTerm = '%'.$validated['search'].'%';
            $q->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhereHas('branch', function ($b) use ($searchTerm) {
                        $b->where('name', 'like', $searchTerm);
                    });
            });
        });

        // Apply dynamic sorting with fallbacks
        $sortBy = $validated['sort_by'] ?? 'created_at';
        $direction = $validated['direction'] ?? 'desc';
        $query->orderBy($sortBy, $direction);

        // Dynamic pagination sizing
        $perPage = $validated['size'] ?? 10;
        $users = $query->paginate($perPage);

        return $this->success($users, 'Users List');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
