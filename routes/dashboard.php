<?php

use Illuminate\Support\Facades\Route;

// ✅ Core
use App\Http\Controllers\Dashboard\PlansController;
use App\Http\Controllers\Dashboard\RolesController;

// ✅ Plans
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\OffersController;
use App\Http\Controllers\Dashboard\SlidersController;

// ✅ Catalog
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Admin\UserSocialMediaController;
use App\Http\Controllers\Dashboard\OfferImagesController;
use App\Http\Controllers\Dashboard\PermissionsController;
use App\Http\Controllers\Dashboard\OfferReportsController;

// ✅ Social/Favorites/Reports
use App\Http\Controllers\Dashboard\PlanFeaturesController;
use App\Http\Controllers\Dashboard\UserProfilesController;
use App\Http\Controllers\Dashboard\SubscriptionsController;
use App\Http\Controllers\Dashboard\FavoriteOffersController;

// ✅ Pending verifications
use App\Http\Controllers\Dashboard\OfferComplaintsController;

// ✅ Roles/Permissions
use App\Http\Controllers\Dashboard\DashboardPaymentController;
use App\Http\Controllers\Dashboard\OfferSocialMediaController;
use App\Http\Controllers\Dashboard\PendingProfileVerificationsController;

/**
 * OPTIONAL: لو عايز تقفل الداش على admin roles فقط
 * Route::middleware(['role:super_admin|admin'])->group(function () { ... });
 */

// Users & Profiles
Route::resource('users', UsersController::class);
Route::resource('user-profiles', UserProfilesController::class);

// Roles / Permissions
Route::resource('roles', RolesController::class);
Route::resource('permissions', PermissionsController::class);

// Plans
Route::resource('plans', PlansController::class);
Route::resource('plan-features', PlanFeaturesController::class);
Route::resource('subscriptions', SubscriptionsController::class);

// Categories / Offers / Sliders
Route::resource('categories', CategoriesController::class);
Route::resource('offers', OffersController::class);
Route::resource('offer-images', OfferImagesController::class);
Route::resource('offer-social-media', OfferSocialMediaController::class);
Route::resource('sliders', SlidersController::class);

// User Social
Route::resource('user-social-media', UserSocialMediaController::class);

// Favorites / Reports / Complaints
Route::resource('favorite-offers', FavoriteOffersController::class);
Route::resource('offer-reports', OfferReportsController::class);
Route::resource('offer-complaints', OfferComplaintsController::class);

// Pending profile verifications
Route::resource('pending-profile-verifications', PendingProfileVerificationsController::class);

Route::resource('payments', DashboardPaymentController::class);

// update status quick route
Route::patch('payments/{payment}/status', [DashboardPaymentController::class, 'updateStatus'])
    ->name('payments.status');
