<?php

namespace App\Repositories\Offer;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Offer\OfferRepositoryInterface;
use App\Models\{FavoriteOffer, Offer, OfferImage, OfferSocialMedia};

class OfferRepository implements OfferRepositoryInterface
{

    public function all($userId = null, $categoryId = null, $city = null, $page = 1, $pageSize = 10)
    {
        $authUser = auth('sanctum')->user();
        // dd($authUser);
        $query = Offer::with(['images', 'socialMedia']);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        // ✅ Filter by auth user's city/country if logged in
        if ($authUser) {
            $userCityValues = array_values(array_filter([
                $authUser->city,
                $authUser->city_ar,
                $authUser->city_en,
            ]));

            $userCountryValues = array_values(array_filter([
                $authUser->governorate_ar,
                $authUser->governorate_en,
            ]));

            $query->where(function ($q) use ($userCityValues, $userCountryValues) {

                // City match: user.city|city_ar|city_en against offer.city_ar|city_en
                if (!empty($userCityValues)) {
                    $q->where(function ($qq) use ($userCityValues) {
                        $qq->whereIn('city_ar', $userCityValues)
                            ->orWhereIn('city_en', $userCityValues);
                    });
                }

                // OR Country match: user.country_ar|country_en against offer.country_ar|country_en
                if (!empty($userCountryValues)) {
                    // لو فيه City شرط فوق، نخليه OR
                    $method = !empty($userCityValues) ? 'orWhere' : 'where';

                    $q->{$method}(function ($qq) use ($userCountryValues) {
                        $qq->whereIn('country_ar', $userCountryValues)
                            ->orWhereIn('country_en', $userCountryValues);
                    });
                }
            });
        }


        // ✅ NEW: filter by city (matches city_ar OR city_en)
        if ($city) {
            $query->where(function ($q) use ($city) {
                $q->where('city_ar', $city)
                    ->orWhere('city_en', $city);
            });
        }

        return $query->paginate($pageSize, ['*'], 'page', $page);
    }



    public function find($id)
    {
        return Offer::with(['user', 'category', 'images', 'socialMedia'])->findOrFail($id);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $offer = Offer::create($data);

            if (!empty($data['images'])) {
                foreach ($data['images'] as $image) {
                    if ($image instanceof \Illuminate\Http\UploadedFile) {
                        // Store file in "public/offers" directory
                        $path = $image->store('offers', 'public');

                        // Save the file path (relative to /storage)
                        OfferImage::create([
                            'offer_id' => $offer->id,
                            'image' => $path,
                        ]);
                    }
                }
            }


            if (!empty($data['social_media'])) {
                foreach ($data['social_media'] as $media) {
                    OfferSocialMedia::create([
                        'offer_id' => $offer->id,
                        'platform' => $media['platform'],
                        'url' => $media['url'],
                    ]);
                }
            }

            return $offer->load(['images', 'socialMedia']);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $offer = Offer::findOrFail($id);
            $offer->update($data);

            /** ------------------------------------------
             * DELETE ONLY SELECTED IMAGES
             * ------------------------------------------ */
            if (!empty($data['delete_images'])) {
                $imagesToDelete = OfferImage::where('offer_id', $id)
                    ->whereIn('id', $data['delete_images'])
                    ->get();

                foreach ($imagesToDelete as $img) {
                    Storage::disk('public')->delete($img->image);
                    $img->delete();
                }
            }

            /** ------------------------------------------
             * ADD NEW IMAGES
             * ------------------------------------------ */
            if (!empty($data['new_images'])) {
                foreach ($data['new_images'] as $file) {

                    // CASE 1: Real uploaded file
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $path = $file->store('offers', 'public');
                        OfferImage::create([
                            'offer_id' => $offer->id,
                            'image' => $path,
                        ]);
                    }

                    // CASE 2: A URL from the client
                    elseif (is_string($file)) {

                        // Remove domain and storage prefix (keep relative path only)
                        $cleanPath = str_replace(url('storage') . '/', '', $file);

                        OfferImage::create([
                            'offer_id' => $offer->id,
                            'image' => $cleanPath,
                        ]);
                    }
                }
            }


            if (isset($data['social_media'])) {
                OfferSocialMedia::where('offer_id', $id)->delete();
                foreach ($data['social_media'] as $media) {
                    OfferSocialMedia::create([
                        'offer_id' => $id,
                        'platform' => $media['platform'],
                        'url' => $media['url'],
                    ]);
                }
            }

            return $offer->load(['images', 'socialMedia']);
        });
    }

    public function delete($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();
        return true;
    }

    // provider offers
    public function providerOffers($status, $search = null)
    {
        $user = Auth::user();

        $query = Offer::where('user_id', $user->id)
            ->with(['images', 'socialMedia']);

        // 🔍 Optional search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%")
                    ->orWhere('location_name', 'like', "%{$search}%");
            });
        }

        // 🕒 Filter by offer status: current / previous
        if ($status) {
            if ($status === 'current') {
                $query->where(function ($q) {
                    $q->whereNull('expiration_date')
                        ->orWhere('expiration_date', '>=', now());
                });
            } elseif ($status === 'previous') {
                $query->where('expiration_date', '<', now());
            }
        }

        // Optional: add sorting if you like
        $query->orderByDesc('created_at');

        return $query->get();
    }


    // provider stats
    public function providerStats()
    {
        $user = Auth::user();
        // Implement stats logic here
        $totalOffers = Offer::where('user_id', $user->id)->count();

        $totalActiveOffers = Offer::where('user_id', $user->id)
            ->where('expiration_date', '>=', now())
            ->count();

        $offerIds = Offer::where('user_id', $user->id)->pluck('id');
        // Assuming Offer model has 'views' and 'likes' columns or relationships
        $numberOfViews = Offer::where('user_id', $user->id)->sum('views');
        $numberOfLikes = FavoriteOffer::whereIn('offer_id', $offerIds)->count();

        // Example: subscription duration from user's subscription start date

        // Get the latest subscription for the user
        $latestSubscription = $user->subscriptions()
            ->latest('start_date')
            ->first();

        if ($latestSubscription) {
            $start = Carbon::parse($latestSubscription->start_date);
            $end = $latestSubscription->expiration_date ? Carbon::parse($latestSubscription->expiration_date) : Carbon::now();

            if ($end->isPast() && $latestSubscription->expiration_date) {
                $subscriptionDuration = 'Expired ' . $end->diffForHumans(null, [
                    'parts' => 2,
                    'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
                ]);
            } else {
                $subscriptionDuration = $start->diffForHumans($end, [
                    'parts' => 2,
                    'syntax' => Carbon::DIFF_ABSOLUTE,
                ]);
            }
        } else {
            $subscriptionDuration = 'No subscription';
        }



        return [
            'total_active_offers' => $totalActiveOffers,
            'total_offers' => $totalOffers,
            'number_of_view' => (int) $numberOfViews,
            'number_of_like' => (int) $numberOfLikes,
            'subscription_duration' => $subscriptionDuration,
        ];
    }

    public function incrementInteraction(int $offerId, string $type): bool
    {
        $offer = Offer::find($offerId);

        if (! $offer) {
            return false;
        }

        switch ($type) {
            case 'view':
                $offer->increment('views');
                break;

            case 'like':
                $offer->increment('likes');
                break;

            default:
                return false;
        }

        return true;
    }


    public function searchOffersAndUsers(?string $name, ?int $userId)
    {
        // 🔹 Search Offers
        $offers = Offer::with('user')
            ->when(!empty($userId), function ($query) use ($userId) {
                $query->where('offers.user_id', '=', $userId);
            })
            ->when(!empty($name), function ($query) use ($name) {
                $query->where(function ($q) use ($name) {
                    $q->where('offers.name', 'like', "%{$name}%")
                        ->orWhereHas('user', function ($uq) use ($name) {
                            $uq->where('name', 'like', "%{$name}%");
                        });
                });
            })->with('images')
            ->latest()
            ->get();

        // 🔹 Search Users
        $users = User::when(!empty($userId), function ($query) use ($userId) {
            $query->where('id', '=', $userId);
        })
            ->when(!empty($name), function ($query) use ($name) {
                $query->where('name', 'like', "%{$name}%")
                    ->orWhere('email', 'like', "%{$name}%")
                    ->orWhere('phone', 'like', "%{$name}%");
            })
            ->where('type', 'provider') // optional: only search providers
            ->latest()
            ->get();

        return [
            'offers' => $offers,
            'users' => $users,
        ];
    }
}
