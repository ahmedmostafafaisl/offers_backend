<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Plan;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Interfaces\Dashboard\SubscriptionsRepositoryInterface;
use App\Http\Requests\Dashboard\Subscriptions\StoreSubscriptionRequest;
use App\Http\Requests\Dashboard\Subscriptions\UpdateSubscriptionRequest;

class SubscriptionsController extends Controller
{
    public function __construct(private SubscriptionsRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
            'user_id' => request('user_id'),
            'plan_id' => request('plan_id'),
            'type' => request('type'),
            'is_active' => request('is_active'),
        ], (int)request('per_page', 10));

        $users = User::orderBy('id', 'desc')->limit(200)->get();
        $plans = Plan::orderBy('name')->get();

        return view('dashboard.subscriptions.index', compact('rows', 'users', 'plans'));
    }

    public function create()
    {
        $users = User::orderBy('id', 'desc')->limit(200)->get();
        $plans = Plan::orderBy('name')->get();
        return view('dashboard.subscriptions.create', compact('users', 'plans'));
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = (bool)($data['is_active'] ?? true);
        $this->repo->create($data);
        return redirect()->route('dashboard.subscriptions.index')->with('success', 'Subscription created');
    }

    public function edit($subscription)
    {
        $row = $this->repo->findOrFail((int)$subscription);
        $users = User::orderBy('id', 'desc')->limit(200)->get();
        $plans = Plan::orderBy('name')->get();
        return view('dashboard.subscriptions.edit', compact('row', 'users', 'plans'));
    }

    public function update(UpdateSubscriptionRequest $request, $subscription)
    {
        $data = $request->validated();
        $data['is_active'] = (bool)($data['is_active'] ?? false);
        $this->repo->update((int)$subscription, $data);
        return redirect()->route('dashboard.subscriptions.index')->with('success', 'Subscription updated');
    }

    public function destroy($subscription)
    {
        $this->repo->delete((int)$subscription);
        return back()->with('success', 'Subscription deleted');
    }
}
