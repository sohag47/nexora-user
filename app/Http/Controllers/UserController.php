<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpsertUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponse;

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(UserRequest $request): JsonResponse
    {
        $users = $this->userService->getPaginated($request->validated());

        /** @var LengthAwarePaginator $users */
        $users->setCollection(UserResource::collection($users->getCollection())->collection);

        return $this->success($users, 'Users List');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpsertUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return $this->created($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userService->findUserById($id);
        if (empty($user)) {
            return $this->notFound();
        }

        $resource = new UserResource($user);

        return $this->success($resource);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);
        if (empty($user)) {
            return $this->notFound();
        }
        $this->userService->updateUser($user, $request->validated());

        return $this->updated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->userService->findUserById($id);
        if (empty($user)) {
            return $this->notFound();
        }
        $this->userService->deleteUser($user);

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

        $resource = $this->userService->dropdown($request);

        return $this->success($resource);
    }
}
