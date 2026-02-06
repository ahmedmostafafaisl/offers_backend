<?php

namespace App\Repositories\Profile;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\PendingProfileVerification;
use Illuminate\Validation\ValidationException;
use App\Interfaces\Profile\ProfileRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function listForUser(User $user): Collection
    {
        return $user->profiles()->latest('id')->get();
    }

    public function findOwnedProfileOrFail(User $user, int $profileId): UserProfile
    {
        return UserProfile::query()
            ->where('id', $profileId)
            ->where('user_id', $user->id)
            ->firstOrFail();
    }

    public function createForUser(User $user, array $data): UserProfile
    {
        // Ensure user_id is enforced
        $data['user_id'] = $user->id;

        // Optional rule: prevent duplicate type per user (if you added unique(user_id,type))
        // If you want allow multiple of same type, remove this and unique index.
        if (isset($data['type'])) {
            $exists = UserProfile::query()
                ->where('user_id', $user->id)
                ->where('type', $data['type'])
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'type' => ['You already have a profile with this type.'],
                ]);
            }
        }

        $profile = UserProfile::create($data);

        // If user has no active profile, set it
        if (!$user->active_profile_id) {
            $user->forceFill(['active_profile_id' => $profile->id])->save();
        }

        return $profile;
    }

    public function updateOwnedProfile(User $user, int $profileId, array $data): UserProfile
    {
        $profile = $this->findOwnedProfileOrFail($user, $profileId);

        // Prevent changing ownership
        unset($data['user_id']);

        // Optional: prevent type change, or validate it
        // unset($data['type']);

        $profile->fill($data)->save();

        return $profile->fresh();
    }

    public function deleteOwnedProfile(User $user, int $profileId): void
    {
        $profile = $this->findOwnedProfileOrFail($user, $profileId);

        // Don't allow deleting active profile
        if ((int) $user->active_profile_id === (int) $profile->id) {
            throw ValidationException::withMessages([
                'profile_id' => ['You cannot delete your active profile. Switch first.'],
            ]);
        }

        $profile->delete();
    }

    public function switchActiveProfile(User $user, UserProfile $profile): UserProfile
    {
        $user->forceFill(['active_profile_id' => $profile->id])->save();
        return $profile->fresh();
    }

    public function startCreateProfile(User $requester, string $phone, string $type): void
    {
        // target user by phone
        $target = User::query()->where('phone', $phone)->first();

        // ✅ مهم: لا تكشف هل الرقم موجود ولا لأ
        if (!$target || !$target->email) {
            return; // controller يرجع نفس الرسالة في كل الحالات
        }

        $otp = (string) random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        PendingProfileVerification::updateOrCreate(
            [
                'requester_user_id' => $requester->id,
                'type' => $type,
                'phone' => $phone,
            ],
            [
                'target_user_id' => $target->id,
                'email' => $target->email,
                'otp_hash' => Hash::make($otp),
                'expires_at' => $expiresAt,
                'verified_at' => null,
            ]
        );

        Mail::raw("Your OTP is: {$otp}", function ($message) use ($target) {
            $message->to($target->email)->subject('Profile OTP Verification');
        });
    }

    public function verifyAndCreateProfile(User $requester, string $phone, string $type, string $otp): UserProfile
    {
        $pending = PendingProfileVerification::query()
            ->where('requester_user_id', $requester->id)
            ->where('type', $type)
            ->where('phone', $phone)
            ->first();

        if (!$pending) {
            throw ValidationException::withMessages([
                'otp' => ['No pending verification found. Start first.'],
            ]);
        }

        if ($pending->verified_at) {
            throw ValidationException::withMessages([
                'otp' => ['This verification was already used.'],
            ]);
        }

        if (!$pending->expires_at || Carbon::now()->greaterThan($pending->expires_at)) {
            throw ValidationException::withMessages([
                'otp' => ['OTP expired. Please start again.'],
            ]);
        }

        if (!$pending->otp_hash || !Hash::check($otp, $pending->otp_hash)) {
            throw ValidationException::withMessages([
                'otp' => ['Invalid OTP.'],
            ]);
        }

        if (!$pending->target_user_id) {
            throw ValidationException::withMessages([
                'phone' => ['Invalid phone or verification expired.'],
            ]);
        }

        $target = User::find($pending->target_user_id);
        if (!$target) {
            throw ValidationException::withMessages([
                'phone' => ['Target user not found.'],
            ]);
        }

        // mark used
        $pending->update(['verified_at' => Carbon::now()]);

        // ✅ create profile under requester user, copy data from target user
        $profile = UserProfile::firstOrCreate(
            ['user_id' => $requester->id, 'type' => $type],
            [
                'linked_user_id' => $target->id,
                'name' => $target->name,
                'phone' => $target->phone,
                'photo' => $target->photo,
                'country' => $target->country,
                'city' => $target->city,

                'whats_app_number' => $target->whats_app_number,
                'store_number' => $target->store_number,
                'store_establish_date' => $target->store_establish_date,
                'tax_number' => $target->tax_number,
                'commercial_registration' => $target->commercial_registration,
            ]
        );

        if (!$requester->active_profile_id) {
            $requester->forceFill(['active_profile_id' => $profile->id])->save();
        }

        return $profile->fresh();
    }

    public function startLink(User $requester, string $phone, string $type): bool
    {
        $target = User::where('phone', $phone)->first();

        if (!$target || !$target->email) {
            return false; // ❌ user not found
        }

        $otp = (string) random_int(100000, 999999);

        PendingProfileVerification::updateOrCreate(
            [
                'requester_user_id' => $requester->id,
                'type' => $type,
                'phone' => $phone,
            ],
            [
                'target_user_id' => $target->id,
                'email' => $target->email,
                'otp_hash' => Hash::make($otp),
                'expires_at' => now()->addMinutes(10),
                'verified_at' => null,
            ]
        );

        Mail::raw("Your OTP is: {$otp}", function ($message) use ($target) {
            $message->to($target->email)->subject('Profile OTP Verification');
        });

        return true; // ✅ user found and OTP sent
    }


    public function verifyAndLink(User $requester, string $phone, string $type, string $otp): array
    {
        $pending = PendingProfileVerification::query()
            ->where('requester_user_id', $requester->id)
            ->where('type', $type)
            ->where('phone', $phone)
            ->first();

        if (!$pending) {
            throw ValidationException::withMessages([
                'otp' => ['No pending verification found. Start first.'],
            ]);
        }

        if ($pending->verified_at) {
            throw ValidationException::withMessages([
                'otp' => ['This OTP was already used.'],
            ]);
        }

        if (!$pending->expires_at || Carbon::now()->greaterThan($pending->expires_at)) {
            throw ValidationException::withMessages([
                'otp' => ['OTP expired. Start again.'],
            ]);
        }

        if (!$pending->otp_hash || !Hash::check($otp, $pending->otp_hash)) {
            throw ValidationException::withMessages([
                'otp' => ['Invalid OTP.'],
            ]);
        }

        if (!$pending->target_user_id) {
            throw ValidationException::withMessages([
                'phone' => ['Invalid phone or verification expired.'],
            ]);
        }

        $target = User::query()->find($pending->target_user_id);
        if (!$target) {
            throw ValidationException::withMessages([
                'phone' => ['Target user not found.'],
            ]);
        }

        // mark used
        $pending->update(['verified_at' => Carbon::now()]);

        // ✅ (1) requester -> target
        // type here is the "destination account type" you want to switch to.
        $requesterProfile = UserProfile::updateOrCreate(
            [
                'user_id' => $requester->id,
                'type'    => $type,
            ],
            [
                'linked_user_id' => $target->id,

                // snapshot fields (optional)
                'name'                  => $target->name,
                'phone'                 => $target->phone,
                'photo'                 => $target->photo,
                'country'               => $target->country,
                'city'                  => $target->city,
                'whats_app_number'      => $target->whats_app_number,
                'store_number'          => $target->store_number,
                'store_establish_date'  => $target->store_establish_date,
                'tax_number'            => $target->tax_number,
                'commercial_registration' => $target->commercial_registration,
            ]
        );

        // ✅ (2) target -> requester (reverse)
        // IMPORTANT: reverse type = requester->type (so target can switch back to requester account type)
        $reverseType = $requester->type;

        $targetProfile = UserProfile::updateOrCreate(
            [
                'user_id' => $target->id,
                'type'    => $reverseType,
            ],
            [
                'linked_user_id' => $requester->id,

                // snapshot fields (optional)
                'name'                  => $requester->name,
                'phone'                 => $requester->phone,
                'photo'                 => $requester->photo,
                'country'               => $requester->country,
                'city'                  => $requester->city,
                'whats_app_number'      => $requester->whats_app_number,
                'store_number'          => $requester->store_number,
                'store_establish_date'  => $requester->store_establish_date,
                'tax_number'            => $requester->tax_number,
                'commercial_registration' => $requester->commercial_registration,
            ]
        );

        return [
            'requester_profile' => $requesterProfile->fresh(),
            'target_profile'    => $targetProfile->fresh(),
            'target_user'       => $target,
        ];
    }

    public function switchAccount(User $currentUser, string $toType, bool $revokeCurrentToken = true): array
    {

        // find link owned by current user to destination type
        $profile = UserProfile::query()
            ->where('user_id', $currentUser->id)
            ->where('type', $toType)
            ->first();
        // dd($profile->user);
        if (!$profile || !$profile->linked_user_id) {
            throw ValidationException::withMessages([
                'to' => ['No linked account found for this switch. Please link accounts first.'],
            ]);
        }

        $targetUser = User::query()->find($profile->linked_user_id);

        if (!$targetUser) {
            throw ValidationException::withMessages([
                'to' => ['Linked user not found.'],
            ]);
        }

        // revoke current token (optional but recommended)
        if ($revokeCurrentToken && $currentUser->currentAccessToken()) {
            $currentUser->currentAccessToken()->delete();
        }

        $newToken = $targetUser->createToken('switch-account')->plainTextToken;

        return [
            'token' => $newToken,
            'user'  => $targetUser,
        ];
    }

    // new method to link by credentials (email + password) instead of OTP

    public function linkByCredentials(User $requester, string $type, string $email, string $password): array
    {
        $target = User::where('email', $email)->first();

        if (!$target) {
            throw ValidationException::withMessages([
                'email' => ['Account not found.'],
            ]);
        }

        if ($target->id === $requester->id) {
            throw ValidationException::withMessages([
                'email' => ['You cannot link to your own account.'],
            ]);
        }

        // ✅ check password
        if (!Hash::check($password, $target->password)) {
            throw ValidationException::withMessages([
                'password' => ['Invalid email or password.'],
            ]);
        }

        // ✅ Only provider/customer can be linked (employee ممنوع)
        if (!in_array($requester->type, ['provider', 'customer'], true)) {
            throw ValidationException::withMessages([
                'type' => ['Only provider/customer accounts can link profiles.'],
            ]);
        }

        if (!in_array($target->type, ['provider', 'customer'], true)) {
            throw ValidationException::withMessages([
                'email' => ['Target account must be provider/customer.'],
            ]);
        }

        // ✅ must be different types (provider <-> customer)
        if ($requester->type === $target->type) {
            throw ValidationException::withMessages([
                'type' => ['Linking requires different types (provider with customer only).'],
            ]);
        }

        // ✅ optional: لو بتبعت type في الريكويست لازم يطابق نوع target الحقيقي
        if (!empty($type) && $target->type !== $type) {
            throw ValidationException::withMessages([
                'type' => ['Target type does not match the requested type.'],
            ]);
        }

        return DB::transaction(function () use ($requester, $target) {

            // =========================================================
            // ✅ One-to-one checks (قبل ما DB يضرب Unique error)
            // =========================================================

            // requester: هل عنده profile مربوط؟
            $requesterProfile = UserProfile::where('user_id', $requester->id)->first();
            if ($requesterProfile && !is_null($requesterProfile->linked_user_id)) {
                throw ValidationException::withMessages([
                    'email' => ['This account is already linked to another account.'],
                ]);
            }

            // requester: هل هو مربوط كـ linked_user_id لحد تاني؟
            $requesterUsedAsLinked = UserProfile::where('linked_user_id', $requester->id)->exists();
            if ($requesterUsedAsLinked) {
                throw ValidationException::withMessages([
                    'email' => ['This account is already linked (as a target) to another account.'],
                ]);
            }

            // target: هل عنده profile مربوط؟
            $targetProfile = UserProfile::where('user_id', $target->id)->first();
            if ($targetProfile && !is_null($targetProfile->linked_user_id)) {
                throw ValidationException::withMessages([
                    'email' => ['Target account is already linked to another account.'],
                ]);
            }

            // target: هل هو مربوط كـ linked_user_id لحد تاني؟
            $targetUsedAsLinked = UserProfile::where('linked_user_id', $target->id)->exists();
            if ($targetUsedAsLinked) {
                throw ValidationException::withMessages([
                    'email' => ['Target account is already linked (as a target) to another account.'],
                ]);
            }

            // =========================================================
            // ✅ Save profiles (type = actual users.type)
            // =========================================================

            // requester profile (user_id unique)
            $requesterProfile = UserProfile::updateOrCreate(
                ['user_id' => $requester->id],
                [
                    'type' => $target->type,
                    'linked_user_id' => $target->id,

                    // snapshot from target (optional)
                    'name' => $target->name,
                    'phone' => $target->phone,
                    'photo' => $target->photo,
                    'country' => $target->country,
                    'city' => $target->city,
                    'whats_app_number' => $target->whats_app_number,
                    'store_number' => $target->store_number,
                    'store_establish_date' => $target->store_establish_date,
                    'tax_number' => $target->tax_number,
                    'commercial_registration' => $target->commercial_registration,
                ]
            );

            // target profile (user_id unique)
            $targetProfile = UserProfile::updateOrCreate(
                ['user_id' => $target->id],
                [
                    'type' => $requester->type,
                    'linked_user_id' => $requester->id,

                    // snapshot from requester (optional)
                    'name' => $requester->name,
                    'phone' => $requester->phone,
                    'photo' => $requester->photo,
                    'country' => $requester->country,
                    'city' => $requester->city,
                    'whats_app_number' => $requester->whats_app_number,
                    'store_number' => $requester->store_number,
                    'store_establish_date' => $requester->store_establish_date,
                    'tax_number' => $requester->tax_number,
                    'commercial_registration' => $requester->commercial_registration,
                ]
            );

            return [
                'requester_profile' => $requesterProfile->fresh(),
                'target_profile'    => $targetProfile->fresh(),
                'target_user'       => $target,
            ];
        });
    }
}
