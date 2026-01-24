<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Plan\PlanResource;
use App\Http\Requests\Plan\StorePlanRequest;
use App\Http\Requests\Plan\UpdatePlanRequest;
use App\Interfaces\Plan\PlanRepositoryInterface;

class PlanController extends Controller
{
    protected $planRepository;

    public function __construct(PlanRepositoryInterface $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    public function index(): JsonResponse
    {
        $data = $this->planRepository->all();

        return response()->json([
            'plans' => PlanResource::collection($data['plans']),
            'subscribed_plan' => $data['subscribed_plan']
                ? new PlanResource($data['subscribed_plan'])
                : null,
            'expiration_date' => $data['expiration_date'] ? $data['expiration_date']->toDateString() : null,
        ]);
    }


    public function show($id): JsonResponse
    {
        $plan = $this->planRepository->find($id);
        return response()->json(new PlanResource($plan));
    }

    public function store(StorePlanRequest $request): JsonResponse
    {
        $plan = $this->planRepository->create($request->validated());
        return response()->json(new PlanResource($plan), 201);
    }

    public function update(UpdatePlanRequest $request, $id): JsonResponse
    {
        $plan = $this->planRepository->update($id, $request->validated());
        return response()->json(new PlanResource($plan));
    }

    public function destroy($id): JsonResponse
    {
        $this->planRepository->delete($id);
        return response()->json(['message' => 'Plan deleted successfully']);
    }
}
