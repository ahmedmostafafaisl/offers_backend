<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Subscription\SubscriptionResource;
use App\Http\Requests\Subscription\StoreSubscriptionRequest;
use App\Http\Requests\Subscription\UpdateSubscriptionRequest;
use App\Interfaces\Subscription\SubscriptionRepositoryInterface;

class SubscriptionController extends Controller
{
    protected $subscriptionRepository;

    public function __construct(SubscriptionRepositoryInterface $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function index(): JsonResponse
    {
        $subscriptions = $this->subscriptionRepository->all();
        return response()->json(SubscriptionResource::collection($subscriptions));
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $user = auth()->user();

        $startDate = Carbon::now();
        $expirationDate = $request->type === 'monthly'
            ? $startDate->copy()->addMonth()
            : $startDate->copy()->addYear();

        $subscriptionData = [
            'user_id' => $user->id,
            'plan_id' => $request->plan_id,
            'type' => $request->type,
            'payment_type' => $request->payment_type,
            'start_date' => $startDate,
            'expiration_date' => $expirationDate,
        ];

        return   $subscription = $this->subscriptionRepository->createWithPayment($subscriptionData);

        return response()->json(new SubscriptionResource($subscription), 201);
    }
    public function show($id): JsonResponse
    {
        $subscription = $this->subscriptionRepository->find($id);
        return response()->json(new SubscriptionResource($subscription));
    }

    public function update(UpdateSubscriptionRequest $request, $id): JsonResponse
    {
        $subscription = $this->subscriptionRepository->update($id, $request->validated());
        return response()->json(new SubscriptionResource($subscription));
    }

    public function destroy($id): JsonResponse
    {
        $this->subscriptionRepository->delete($id);
        return response()->json(['message' => 'Subscription deleted successfully']);
    }


    public function userSubscriptions(): JsonResponse
    {
        $subscriptions = $this->subscriptionRepository->userSubscriptions();
        return response()->json(SubscriptionResource::collection($subscriptions));
    }
}
