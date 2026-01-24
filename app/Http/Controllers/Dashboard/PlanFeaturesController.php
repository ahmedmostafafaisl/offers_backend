<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Plan;
use App\Http\Controllers\Controller;
use App\Interfaces\Dashboard\PlanFeaturesRepositoryInterface;
use App\Http\Requests\Dashboard\PlanFeatures\StorePlanFeatureRequest;
use App\Http\Requests\Dashboard\PlanFeatures\UpdatePlanFeatureRequest;

class PlanFeaturesController extends Controller
{
    public function __construct(private PlanFeaturesRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
            'plan_id' => request('plan_id'),
        ], (int)request('per_page', 10));

        $plans = Plan::orderBy('name')->get();
        return view('dashboard.plan_features.index', compact('rows', 'plans'));
    }

    public function create()
    {
        $plans = Plan::orderBy('name')->get();
        return view('dashboard.plan_features.create', compact('plans'));
    }

    public function store(StorePlanFeatureRequest $request)
    {
        $this->repo->create($request->validated());
        return redirect()->route('dashboard.plan-features.index')->with('success', 'Plan feature created');
    }

    public function edit($plan_feature)
    {
        $row = $this->repo->findOrFail((int)$plan_feature);
        $plans = Plan::orderBy('name')->get();
        return view('dashboard.plan_features.edit', compact('row', 'plans'));
    }

    public function update(UpdatePlanFeatureRequest $request, $plan_feature)
    {
        $this->repo->update((int)$plan_feature, $request->validated());
        return redirect()->route('dashboard.plan-features.index')->with('success', 'Plan feature updated');
    }

    public function destroy($plan_feature)
    {
        $this->repo->delete((int)$plan_feature);
        return back()->with('success', 'Plan feature deleted');
    }
}
