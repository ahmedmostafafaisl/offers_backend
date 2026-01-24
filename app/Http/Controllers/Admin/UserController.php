<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Interfaces\User\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->getAllUsers();
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userRepository->createUser($request->validated());
        return new UserResource($user);
    }

    public function show($id)
    {
        $user = $this->userRepository->getUserById($id);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userRepository->updateUser($id, $request->validated());
        return new UserResource($user);
    }
    public function updateByToken(UpdateUserRequest $request)
    {
        $authUser = auth()->user();
        $user = $this->userRepository->updateUser($authUser->id, $request->validated());
        return new UserResource($user);
    }
    public function destroy($id)
    {
        $this->userRepository->deleteUser($id);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
