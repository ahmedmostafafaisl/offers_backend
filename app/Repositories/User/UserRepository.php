<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\UserSocialMedia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function createUser(array $data)
    {
        if (isset($data['photo']) && $data['photo']->isValid()) {
            if ($data['photo'] instanceof \Illuminate\Http\UploadedFile) {
                // Store file in "public/offers" directory
                $path = $data['photo']->store('users', 'public');

                // Save the file path (relative to /storage)
                $data['photo'] = $path;
            }
        }
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function updateUser($id, array $data)
    {
        $user = User::findOrFail($id);

        // ✅ Handle photo upload first
        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile && $data['photo']->isValid()) {
            // Optionally delete old photo
            if ($user->photo && file_exists(public_path('storage/' . $user->photo))) {
                unlink(public_path('storage/' . $user->photo));
            }

            // Store new photo
            $path = $data['photo']->store('users', 'public');
            $data['photo'] = $path;
        } else {
            unset($data['photo']); // Prevent overwriting with invalid tmp path
        }

        // ✅ Hash password if present
        if (isset($data['password'])) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        }

        // ✅ Update user info
        $user->update($data);

        // ✅ Handle social media links
        if (isset($data['social_media']) && is_array($data['social_media'])) {
            $user->socialMedia()->delete();

            foreach ($data['social_media'] as $social) {
                if (!empty($social['platform']) && !empty($social['url'])) {
                    \App\Models\UserSocialMedia::create([
                        'user_id' => $user->id,
                        'platform' => $social['platform'],
                        'url' => $social['url'],
                    ]);
                }
            }
        }

        return $user->load('socialMedia');
    }


    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}
