<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Interfaces\Dashboard\PendingProfileVerificationsRepositoryInterface;
use App\Http\Requests\Dashboard\PendingProfileVerifications\StorePendingProfileVerificationRequest;
use App\Http\Requests\Dashboard\PendingProfileVerifications\UpdatePendingProfileVerificationRequest;

class PendingProfileVerificationsController extends Controller
{
    public function __construct(private PendingProfileVerificationsRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
            'type' => request('type'),
            'requester_user_id' => request('requester_user_id'),
            'target_user_id' => request('target_user_id'),
            'verified' => request('verified'),
        ], (int) request('per_page', 10));

        $users = User::orderBy('id', 'desc')->limit(300)->get();

        return view('dashboard.pending_verifications.index', compact('rows', 'users'));
    }

    public function create()
    {
        $users = User::orderBy('id', 'desc')->limit(300)->get();
        return view('dashboard.pending_verifications.create', compact('users'));
    }

    public function store(StorePendingProfileVerificationRequest $request)
    {
        $this->repo->create($request->validated());
        return redirect()->route('dashboard.pending-profile-verifications.index')->with('success', 'Pending verification created');
    }

    public function edit($pending_profile_verification)
    {
        $row = $this->repo->findOrFail((int)$pending_profile_verification);
        $users = User::orderBy('id', 'desc')->limit(300)->get();
        return view('dashboard.pending_verifications.edit', compact('row', 'users'));
    }

    public function update(UpdatePendingProfileVerificationRequest $request, $pending_profile_verification)
    {
        $this->repo->update((int)$pending_profile_verification, $request->validated());
        return redirect()->route('dashboard.pending-profile-verifications.index')->with('success', 'Pending verification updated');
    }

    public function destroy($pending_profile_verification)
    {
        $this->repo->delete((int)$pending_profile_verification);
        return back()->with('success', 'Pending verification deleted');
    }
}
