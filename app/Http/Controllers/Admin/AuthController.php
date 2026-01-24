<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\Auth\{
    RegisterCustomerRequest,
    RegisterProviderRequest,
    SendOtpRequest,
    VerifyOtpRequest,
    VerifyPinCodeRequest,
    ForgetPasswordRequest,
    LoginRequest
};
use App\Http\Resources\User\UserResource;

class AuthController extends Controller
{
    /**
     * ✅ Register new customer
     */
    /**
     * ✅ Register new customer
     */
    public function registerCustomer(RegisterCustomerRequest $request)
    {
        $data = $request->validated();
        $data['type'] = 'customer';
        $data['password'] = Hash::make($data['password']);
        $data['otp'] = rand(100000, 999999);

        $user = User::create($data);

        // Send OTP via email
        if (!empty($user->email)) {
            $this->sendOtpMail($user->email, $user->otp);
        }

        // 🔑 Create Sanctum token


        return response()->json([
            'status' => true,
            'message' => 'Customer registered successfully. OTP sent to email.',

        ]);
    }

    /**
     * ✅ Register new provider
     */
    public function registerProvider(RegisterProviderRequest $request)
    {
        $data = $request->validated();
        $data['type'] = 'provider';
        $data['password'] = Hash::make($data['password']);
        $data['otp'] = rand(100000, 999999);

        $user = User::create($data);

        // Send OTP via email
        if (!empty($user->email)) {
            $this->sendOtpMail($user->email, $user->otp);
        }

        // 🔑 Create Sanctum token

        return response()->json([
            'status' => true,
            'message' => 'Provider registered successfully. OTP sent to email.',

        ]);
    }

    /**
     * 📧 Send OTP again
     */
    public function sendOtp(SendOtpRequest $request)
    {
        $user = $this->findUser($request);
        $user->otp = rand(100000, 999999);
        $user->save();

        $this->sendOtpMail($user->email, $user->otp);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully.',
        ]);
    }

    /**
     * 🔑 Verify OTP
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {

        $user = $this->findUser($request);

        if ($user->otp === $request->otp) {
            $user->email_verified_at = now();
            $user->otp = null;
            $user->save();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(
                [
                    'status' => true,
                    'message' => 'OTP verified successfully.',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                        'token_type' => 'Bearer',
                    ],
                ]
            );
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP code.',
        ], 400);
    }

    /**
     * 🔒 Verify Pin Code (4 digits)
     */
    public function verifyPinCode(VerifyPinCodeRequest $request)
    {
        $user = $this->findUser($request);

        if ($user->pin_code === $request->pin_code) {
            return response()->json([
                'status' => true,
                'message' => 'Pin code verified successfully.',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid pin code.',
        ], 400);
    }

    /**
     * 🔁 Forget Password
     */
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $user = $this->findUser($request);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * 🧩 Helper to find user by email or phone
     */
    private function findUser(Request $request)
    {
        return User::where('email', $request->email)
            // ->orWhere('phone', $request->phone)
            ->firstOrFail();
    }

    /**
     * ✉️ Helper to send OTP mail
     */
    private function sendOtpMail($email, $otp)
    {
        Mail::raw("Your verification code is: {$otp}", function ($message) use ($email) {
            $message->to($email)
                ->subject('Your OTP Code');
        });
    }

    public function login(LoginRequest $request)
    {

        $user = User::where('email', $request->email)
            ->whereNotNull('email_verified_at')
            // ->orWhere('phone', $request->phone)
            ->with('socialMedia')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        if ($user->type === 'provider') {
            $isCompleteInformation = !(
                empty($user->photo) ||
                empty($user->address_ar) ||
                empty($user->address_en) ||

                empty($user->store_number)

            );
        } else {
            $isCompleteInformation = !(
                empty($user->address_ar) ||
                empty($user->address_en)
            );
        }
        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
            'user_type' => $user->type,
            'is_complete_information' => $isCompleteInformation,
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully.',
        ]);
    }


    public function me(Request $request)
    {
        return response()->json([
            'status' => true,
            'user' => new UserResource($request->user()->load('socialMedia')),
        ]);
    }


    public function refreshToken(Request $request)
    {
        $user = $request->user();

        // Delete old tokens
        $user->tokens()->delete();

        // Create a new one
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Token refreshed successfully.',
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();

        $user->update([
            'fcm_token' => $request->input('fcm_token'),
        ]);

        return $this->setCode(code: 200)->setData([])->setMessage('FCM token updated successfully.')->send();
    }
}
