<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\PaymentController;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OfferReportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\FavoriteOfferController;
use App\Http\Controllers\Admin\MoyasarPaymentController;
use App\Http\Controllers\Admin\OfferComplaintController;
use App\Http\Controllers\Admin\Profile\ProfileController;
use App\Http\Controllers\Admin\UserSocialMediaController;
use App\Http\Controllers\Admin\Payment\PaymentController as AdminPaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('{id}', [UserController::class, 'show']);
    Route::put('{id}', [UserController::class, 'update']);
    Route::delete('{id}', [UserController::class, 'destroy']);
    Route::post('updateByToken', [UserController::class, 'updateByToken'])->middleware('auth:sanctum');
});




Route::prefix('auth')->group(function () {
    Route::post('register/customer', [AuthController::class, 'registerCustomer']);
    Route::post('register/provider', [AuthController::class, 'registerProvider']);
    Route::post('send-otp', [AuthController::class, 'sendOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('verify-pin', [AuthController::class, 'verifyPinCode']);
    Route::post('forget-password', [AuthController::class, 'forgetPassword']);
    Route::post('login', [AuthController::class, 'login']);
    //activate account
    Route::post('activate', [AuthController::class, 'activateAccount']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refreshToken']);
        // update fcm token
        Route::post('update-fcm-token', [AuthController::class, 'updateFcmToken']);
        // deactivate account
        Route::post('deactivate', [AuthController::class, 'deactivateAccount']);
    });
});


Route::apiResource('plans', PlanController::class);

Route::apiResource('subscriptions', SubscriptionController::class);
Route::apiResource('categories', CategoryController::class);

Route::apiResource('offers', OfferController::class)
    ->only(['index', 'show']);
Route::middleware(['auth:sanctum'])->group(function () {


    Route::apiResource('offers', OfferController::class)
        ->only(['store', 'update', 'destroy'])
        ->middleware('provider.subscription');
});



Route::apiResource('user-social-media', UserSocialMediaController::class);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('subscriptions', SubscriptionController::class);
    Route::get('user-subscriptions', [SubscriptionController::class, 'userSubscriptions']);
    // favorite offers
    Route::get('favorite-offers', [FavoriteOfferController::class, 'index']);
    Route::post('favorite-offers', [FavoriteOfferController::class, 'store']);
    Route::delete('favorite-offers/{id}', [FavoriteOfferController::class, 'destroy']);
    Route::post('toggle/offer', [FavoriteOfferController::class, 'toggleFavorite']);
    // provider offers
    Route::post('provider-offers', [OfferController::class, 'providerOffers']);
});


// user stats
Route::middleware('auth:sanctum')->get('/provider/stats', [OfferController::class, 'providerStats']);

// increment offer interaction
Route::middleware('auth:sanctum')->post('offers/{offer}/interact', [OfferController::class, 'interact']);


Route::prefix('sliders')->group(function () {
    Route::get('/', [SliderController::class, 'index']);
    Route::get('/{id}', [SliderController::class, 'show']);
    Route::post('/', [SliderController::class, 'store']);
    Route::post('/{id}', [SliderController::class, 'update']); // or use PUT/PATCH
    Route::delete('/{id}', [SliderController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('offer-reports', OfferReportController::class);
    Route::apiResource('offer-complaints', OfferComplaintController::class);

    // Complaints
    Route::get('complaints/my', [OfferComplaintController::class, 'myComplaints']);
    Route::get('complaints/on-my-offers', [OfferComplaintController::class, 'complaintsOnMyOffers']);

    // Reports
    Route::get('reports/my', [OfferReportController::class, 'myReports']);
    Route::get('reports/on-my-offers', [OfferReportController::class, 'reportsOnMyOffers']);
});


Route::post('offers_search', [OfferController::class, 'search']);

// switch user profile
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me/profiles', [ProfileController::class, 'index']);
    // ✅ Two-step profile creation
    // Two-step link (OTP)
    Route::post('/profiles/start', [ProfileController::class, 'start'])->middleware('throttle:5,1');
    Route::post('/profiles/verify', [ProfileController::class, 'verify'])->middleware('throttle:10,1');
    Route::get('/profiles/{profileId}', [ProfileController::class, 'show']);
    Route::put('/profiles/{profileId}', [ProfileController::class, 'update']);
    Route::delete('/profiles/{profileId}', [ProfileController::class, 'destroy']);
    // switch
    Route::post('/me/switch-account', [ProfileController::class, 'switchAccount'])->middleware('throttle:10,1');
    Route::get('/me/linked-profiles', [ProfileController::class, 'linkedProfiles']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profiles/link-by-credentials', [ProfileController::class, 'linkByCredentials']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/read/{id}', [NotificationController::class, 'markRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead']);
});

Route::post('/notifications/test-push', [NotificationController::class, 'testPush']);


// Geidea payment routes
Route::post('/geidea/payment/process', [PaymentController::class, 'paymentProcess']);
Route::match(['GET', 'POST'], '/geidea/payment/callback', [PaymentController::class, 'callBack']);
Route::get('/geidea/payments/{payment_id}', [PaymentController::class, 'details']);

// Moyasar payment routes

Route::post('/moyasar/payment/process', [MoyasarPaymentController::class, 'paymentProcess']);
Route::match(['GET', 'POST'], '/moyasar/payment/callback', [MoyasarPaymentController::class, 'callBack'])->name('moyasar.payment.callback');
Route::get('/moyasar/payment/{payment_id}', [MoyasarPaymentController::class, 'show'])
    ->name('moyasar.payment.show');



Route::middleware('auth:sanctum')->group(function () {

    Route::get('/payments', [AdminPaymentController::class, 'index']);
    Route::get('/payments/{id}', [AdminPaymentController::class, 'show']);
    Route::post('/payments', [AdminPaymentController::class, 'store']);
    Route::put('/payments/{id}', [AdminPaymentController::class, 'update']);
    Route::delete('/payments/{id}', [AdminPaymentController::class, 'destroy']);

    Route::patch('/payments/{id}/status', [AdminPaymentController::class, 'updateStatus']);

    // provider payments
    Route::get('/provider/payments', [AdminPaymentController::class, 'providerPayments']);
});

Route::get('logout/users', function (Request $request) {
    PersonalAccessToken::query()->delete();
});


// check active subscription middleware
Route::middleware([
    'auth:sanctum',
    'provider.subscription'
])->get('check/active/subscription', function () {
    return response()->json(['status' => true, 'message' => 'Active subscription found.'], 200);
});
