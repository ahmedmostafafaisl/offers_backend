<?php

namespace App\Http\Requests\Concerns;

use App\Models\Plan;
use Illuminate\Validation\Validator;

/**
 * A plan can offer only some billing types (quarterly / semi_annual / annual).
 * Reject subscriptions that pick a type the plan does not offer (price is NULL).
 */
trait ChecksPlanOffersType
{
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->has('plan_id') || $validator->errors()->has('type')) {
                return;
            }

            $planId = $this->input('plan_id');
            $type = $this->input('type');

            if (!$planId || !$type) {
                return;
            }

            $plan = Plan::find($planId);

            if ($plan && !$plan->offersType($type)) {
                $validator->errors()->add('type', __('dashboard.plan_type_not_offered'));
            }
        });
    }
}
