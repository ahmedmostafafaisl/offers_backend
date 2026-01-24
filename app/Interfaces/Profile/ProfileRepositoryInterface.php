<?php

namespace App\Interfaces\Profile;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Collection;

interface ProfileRepositoryInterface
{

    public function listForUser(User $user): Collection;

    public function findOwnedProfileOrFail(User $user, int $profileId): UserProfile;

    public function switchActiveProfile(User $user, UserProfile $profile): UserProfile;


    public function createForUser(User $user, array $data): UserProfile;

    public function updateOwnedProfile(User $user, int $profileId, array $data): UserProfile;

    public function deleteOwnedProfile(User $user, int $profileId): void;

    public function startCreateProfile(\App\Models\User $requester, string $phone, string $type): void;

    public function verifyAndCreateProfile(\App\Models\User $requester, string $phone, string $type, string $otp): \App\Models\UserProfile;
    public function startLink(User $requester, string $phone, string $type): bool;
    public function verifyAndLink(User $requester, string $phone, string $type, string $otp): array;
    public function switchAccount(User $currentUser, string $toType, bool $revokeCurrentToken = true): array;
}
