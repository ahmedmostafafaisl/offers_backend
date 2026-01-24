<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Interfaces\Dashboard\PlansRepositoryInterface;
use App\Http\Requests\Dashboard\Plans\StorePlanRequest;
use App\Http\Requests\Dashboard\Plans\UpdatePlanRequest;

class PlansController extends Controller
{
    public function __construct(private PlansRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate(['search' => request('search')], (int)request('per_page', 10));
        return view('dashboard.plans.index', compact('rows'));
    }

    public function create()
    {
        return view('dashboard.plans.create');
    }

    public function store(StorePlanRequest $request)
    {
        $this->repo->createWithFeatures($request->validated());
        return redirect()->route('dashboard.plans.index')->with('success', 'Plan created');
    }

    public function edit($plan)
    {
        $row = $this->repo->findOrFail((int)$plan);
        return view('dashboard.plans.edit', compact('row'));
    }

    public function update(UpdatePlanRequest $request, $plan)
    {
        $this->repo->updateWithFeatures((int)$plan, $request->validated());
        return redirect()->route('dashboard.plans.index')->with('success', 'Plan updated');
    }

    public function show($id)
    {
        $row = $this->repo->findOrFail((int)$id); // with features
        return view('dashboard.plans.show', compact('row'));
    }

    public function destroy($plan)
    {
        $this->repo->delete((int)$plan);
        return back()->with('success', 'Plan deleted');
    }
}
