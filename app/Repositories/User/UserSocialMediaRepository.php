<?php

namespace App\Repositories\User;

use App\Models\UserSocialMedia;
use App\Interfaces\User\UserSocialMediaRepositoryInterface;

class UserSocialMediaRepository implements UserSocialMediaRepositoryInterface
{
    public function all()
    {
        return UserSocialMedia::with('user')->get();
    }

    public function find($id)
    {
        return UserSocialMedia::with('user')->findOrFail($id);
    }

    public function create(array $data)
    {
        return UserSocialMedia::create($data);
    }

    public function update($id, array $data)
    {
        $social = $this->find($id);
        $social->update($data);
        return $social;
    }

    public function delete($id)
    {
        $social = $this->find($id);
        return $social->delete();
    }
}
