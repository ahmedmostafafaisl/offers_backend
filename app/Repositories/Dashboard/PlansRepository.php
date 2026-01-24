<?php

namespace App\Repositories\Dashboard;

use App\Interfaces\Dashboard\PlansRepositoryInterface;
use App\Models\Plan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PlansRepository implements PlansRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $q = Plan::query()->withCount('features')->latest();

        // optional filters
        if (!empty($filters['q'])) {
            $q->where('name', 'like', '%' . $filters['q'] . '%');
        }

        return $q->paginate($perPage);
    }

    public function findOrFail(int $id)
    {
        return Plan::with('features')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Plan::create($data);
    }

    public function update(int $id, array $data)
    {
        $plan = Plan::findOrFail($id);
        $plan->update($data);
        return $plan;
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $plan = Plan::findOrFail($id);
            $plan->features()->delete();
            return (bool) $plan->delete();
        });
    }

    // ✅ one module: create plan + features
    public function createWithFeatures(array $data)
    {
        return DB::transaction(function () use ($data) {
            $features = $data['features'] ?? [];
            unset($data['features']);

            $plan = Plan::create($data);

            foreach ($features as $f) {
                if (!empty($f['name'])) {
                    $plan->features()->create([
                        'name' => $f['name'],
                        'description' => $f['description'] ?? null,
                    ]);
                }
            }

            return $plan->load('features');
        });
    }

    // ✅ one module: update plan + sync features
    public function updateWithFeatures(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $plan = Plan::with('features')->findOrFail($id);

            $features = $data['features'] ?? [];
            unset($data['features']);

            $plan->update($data);

            $keepIds = [];

            foreach ($features as $f) {
                if (empty($f['name'])) continue;

                // existing feature (has id)
                if (!empty($f['id'])) {
                    $feature = $plan->features()->where('id', $f['id'])->first();
                    if ($feature) {
                        $feature->update([
                            'name' => $f['name'],
                            'description' => $f['description'] ?? null,
                        ]);
                        $keepIds[] = $feature->id;
                    }
                } else {
                    // new feature
                    $new = $plan->features()->create([
                        'name' => $f['name'],
                        'description' => $f['description'] ?? null,
                    ]);
                    $keepIds[] = $new->id;
                }
            }

            // delete removed
            $plan->features()->whereNotIn('id', $keepIds)->delete();

            return $plan->load('features');
        });
    }
}
