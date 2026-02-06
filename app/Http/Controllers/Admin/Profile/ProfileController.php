<?php

namespace App\Http\Controllers\Admin\Profile;


use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Profile\ProfileResource;
use App\Http\Requests\Profile\StartProfileRequest;
use App\Http\Requests\Profile\StoreProfileRequest;
use App\Http\Requests\Profile\SwitchProfileRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\VerifyProfileOtpRequest;
use App\Interfaces\Profile\ProfileRepositoryInterface;
use App\Http\Requests\Profile\LinkProfileByCredentialsRequest;

class ProfileController extends Controller
{
    public function __construct(private ProfileRepositoryInterface $profiles) {}

    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'active_profile_id' => $user->active_profile_id,
            'profiles' => ProfileResource::collection($this->profiles->listForUser($user)),
        ]);
    }

    public function store(StoreProfileRequest $request)
    {
        $profile = $this->profiles->createForUser($request->user(), $request->validated());

        return response()->json([
            'message' => 'Profile created successfully',
            'profile' => new ProfileResource($profile),
        ], 201);
    }

    public function show(Request $request, int $profileId)
    {
        $profile = $this->profiles->findOwnedProfileOrFail($request->user(), $profileId);

        return new ProfileResource($profile);
    }

    public function update(UpdateProfileRequest $request, int $profileId)
    {
        $profile = $this->profiles->updateOwnedProfile($request->user(), $profileId, $request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => new ProfileResource($profile),
        ]);
    }

    public function destroy(Request $request, int $profileId)
    {
        $this->profiles->deleteOwnedProfile($request->user(), $profileId);

        return response()->json([
            'message' => 'Profile deleted successfully',
        ]);
    }



    public function start(StartProfileRequest $request)
    {
        $found = $this->profiles->startLink(
            $request->user(),
            $request->phone,
            $request->type
        );

        if (!$found) {
            return response()->json([
                'status' => false,
                'message' => 'User not found with this phone number please register first.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'OTP has been sent to the user email.',
        ]);
    }



    public function verify(VerifyProfileOtpRequest $request)
    {
        $result = $this->profiles->verifyAndLink(
            $request->user(),
            $request->phone,
            $request->type,
            $request->otp
        );

        return response()->json([
            'status'  => true,
            'message' => 'Profiles linked successfully (both directions).',
            'profiles' => [
                'requester_profile' => new ProfileResource($result['requester_profile']),
                'target_profile'    => new ProfileResource($result['target_profile']),
            ],
        ], 201);
    }

    public function switchAccount(SwitchProfileRequest $request)
    {
        $out = $this->profiles->switchAccount(
            $request->user(),
            $request->to,
            true
        );

        $newUser = $out['user'];
        if (!$newUser->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'The target profile is deactivated.',
            ], 403);
        }
        // ✅ Set authenticated user for THIS request context
        Auth::setUser($newUser);

        return response()->json([
            'status'  => true,
            'message' => 'Switched successfully',
            'token'   => $out['token'],
            'user'    => new UserResource($newUser->load('socialMedia')),
        ]);
    }

    public function linkedProfiles(Request $request)
    {
        $user = $request->user();
        // dd($user);
        $profiles = UserProfile::query()
            ->where('user_id', $user->id)
            ->where('type', '!=', $user->type)
            ->latest('id')
            ->get();

        return response()->json([
            'status'   => true,
            'profiles' => ProfileResource::collection($profiles),
        ]);
    }

    //  new method to find profile by credentials (email + password) and link it to current user


    public function linkByCredentials(LinkProfileByCredentialsRequest $request)
    {
        $result = $this->profiles->linkByCredentials(
            $request->user(),
            $request->type,
            $request->email,
            $request->password
        );

        return response()->json([
            'status'  => true,
            'message' => 'Profiles linked successfully (both directions).',
            'profiles' => [
                'requester_profile' => new ProfileResource($result['requester_profile']),
                'target_profile'    => new ProfileResource($result['target_profile']),
            ],
        ], 201);
    }
}
