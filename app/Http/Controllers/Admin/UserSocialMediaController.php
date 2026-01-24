<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserSocialMediaResource;
use App\Http\Requests\User\StoreUserSocialMediaRequest;
use App\Http\Requests\User\UpdateUserSocialMediaRequest;
use App\Interfaces\User\UserSocialMediaRepositoryInterface;

class UserSocialMediaController extends Controller
{
    private UserSocialMediaRepositoryInterface $repository;

    public function __construct(UserSocialMediaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $socials = $this->repository->all();
        return UserSocialMediaResource::collection($socials);
    }

    public function show($id)
    {
        return new UserSocialMediaResource($this->repository->find($id));
    }

    public function store(StoreUserSocialMediaRequest $request)
    {
        $social = $this->repository->create($request->validated());
        return new UserSocialMediaResource($social);
    }

    public function update(UpdateUserSocialMediaRequest $request, $id)
    {
        $social = $this->repository->update($id, $request->validated());
        return new UserSocialMediaResource($social);
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json(['message' => 'User social media deleted successfully.']);
    }
}
