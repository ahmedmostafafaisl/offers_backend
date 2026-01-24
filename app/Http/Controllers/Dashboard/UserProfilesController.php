<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Dashboard\UserProfilesRepositoryInterface;
use App\Http\Requests\Dashboard\UserProfiles\StoreUserProfileRequest;
use App\Http\Requests\Dashboard\UserProfiles\UpdateUserProfileRequest;

class UserProfilesController extends Controller
{
    public function __construct(private UserProfilesRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
            'type' => request('type'),
            'user_id' => request('user_id'),
            'linked_user_id' => request('linked_user_id'),
        ], (int)request('per_page', 10));

        $users = User::orderBy('id', 'desc')->limit(200)->get();
        return view('dashboard.user_profiles.index', compact('rows', 'users'));
    }

    public function create()
    {
        $users = User::orderBy('id', 'desc')->limit(200)->get();
        return view('dashboard.user_profiles.create', compact('users'));
    }

    public function store(StoreUserProfileRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo_file')) {
            $data['photo'] = $request->file('photo_file')->store('users', 'public');
        }

        $this->repo->create($data);
        return redirect()->route('dashboard.user-profiles.index')->with('success', 'User profile created');
    }

    public function edit($user_profile)
    {
        $row = $this->repo->findOrFail((int)$user_profile);
        $users = User::orderBy('id', 'desc')->limit(200)->get();
        return view('dashboard.user_profiles.edit', compact('row', 'users'));
    }

    public function update(UpdateUserProfileRequest $request, $user_profile)
    {
        $row = $this->repo->findOrFail((int)$user_profile);
        $data = $request->validated();

        if ($request->hasFile('photo_file')) {
            if ($row->photo) Storage::disk('public')->delete($row->photo);
            $data['photo'] = $request->file('photo_file')->store('users', 'public');
        }

        $this->repo->update((int)$user_profile, $data);
        return redirect()->route('dashboard.user-profiles.index')->with('success', 'User profile updated');
    }

    public function destroy($user_profile)
    {
        $row = $this->repo->findOrFail((int)$user_profile);
        if ($row->photo) Storage::disk('public')->delete($row->photo);

        $this->repo->delete((int)$user_profile);
        return back()->with('success', 'User profile deleted');
    }
}
