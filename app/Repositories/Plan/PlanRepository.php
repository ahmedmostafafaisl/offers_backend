<?php

namespace App\Repositories\Plan;

use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Plan\PlanRepositoryInterface;

class PlanRepository implements PlanRepositoryInterface
{
    public function all()
    {
        $user = auth('sanctum')->user();

        // ✅ Fetch all plans
        $plans = Plan::with('features')->get();

        $subscribedPlan = null;
        $latestSubscription = null;
        if ($user) {
            // ✅ Get user's latest subscription with type (monthly/annually)
            $latestSubscription = $user->subscriptions()
                ->latest('start_date')
                ->with('plan.features')
                ->first();

            if ($latestSubscription && $latestSubscription->plan) {
                $plan = $latestSubscription->plan;
                $type = $latestSubscription->type; // monthly or annually

                // Add a field for the price type
                $plan->subscription_type = $type;

                // Add the price based on type
                $plan->subscription_price = $type === 'annually'
                    ? $plan->annually_price
                    : $plan->monthly_price;

                $subscribedPlan = $plan;
            }
        }

        return [
            'plans' => $plans,
            'subscribed_plan' => $subscribedPlan,
            'expiration_date' => $latestSubscription ? $latestSubscription->expiration_date : null,
        ];
    }



    public function find($id)
    {
        return Plan::with('features')->findOrFail($id);
    }

    public function create(array $data)
    {
        $plan = Plan::create($data);

        if (isset($data['features']) && is_array($data['features'])) {
            foreach ($data['features'] as $feature) {
                $plan->features()->create($feature);
            }
        }

        return $plan->load('features');
    }

    public function update($id, array $data)
    {
        $plan = Plan::findOrFail($id);
        $plan->update($data);

        if (isset($data['features'])) {
            $plan->features()->delete();
            foreach ($data['features'] as $feature) {
                $plan->features()->create($feature);
            }
        }

        return $plan->load('features');
    }

    public function delete($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();
        return true;
    }
}
