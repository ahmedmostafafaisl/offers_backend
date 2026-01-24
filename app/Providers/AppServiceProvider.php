<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Plan\PlanRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Offer\OfferRepository;
use App\Repositories\Slider\SliderRepository;
use App\Repositories\Dashboard\BaseRepository;
use App\Services\Payment\GeideaPaymentService;
use App\Interfaces\User\FavoriteOfferInterface;
use App\Repositories\Dashboard\PlansRepository;
use App\Repositories\Dashboard\UsersRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Profile\ProfileRepository;
use App\Interfaces\Plan\PlanRepositoryInterface;
use App\Interfaces\User\UserRepositoryInterface;
use App\Repositories\Dashboard\OffersRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Dashboard\SlidersRepository;
use App\Repositories\Offer\OfferReportRepository;
use App\Interfaces\Offer\OfferRepositoryInterface;
use App\Repositories\Dashboard\PaymentsRepository;
use App\Repositories\User\FavoriteOfferRepository;
use App\Interfaces\Payment\PaymentGatewayInterface;
use App\Interfaces\Slider\SliderRepositoryInterface;
use App\Repositories\Api\PushNotificationRepository;
use App\Repositories\Dashboard\CategoriesRepository;
use App\Repositories\Offer\OfferComplaintRepository;
use App\Repositories\User\UserSocialMediaRepository;
use App\Interfaces\Dashboard\BaseRepositoryInterface;
use App\Repositories\Dashboard\OfferImagesRepository;
use App\Interfaces\Dashboard\PlansRepositoryInterface;
use App\Interfaces\Dashboard\UsersRepositoryInterface;
use App\Interfaces\Payment\PaymentRepositoryInterface;
use App\Interfaces\Profile\ProfileRepositoryInterface;
use App\Repositories\Dashboard\OfferReportsRepository;
use App\Repositories\Dashboard\PlanFeaturesRepository;
use App\Repositories\Dashboard\UserProfilesRepository;
use App\Interfaces\Dashboard\OffersRepositoryInterface;
use App\Repositories\Dashboard\SubscriptionsRepository;
use App\Services\Payment\Moyasar\MoyasarPaymentService;
use App\Interfaces\Category\CategoryRepositoryInterface;
use App\Interfaces\Dashboard\SlidersRepositoryInterface;
use App\Interfaces\Offer\OfferReportRepositoryInterface;
use App\Repositories\Dashboard\FavoriteOffersRepository;
use App\Interfaces\Dashboard\PaymentsRepositoryInterface;
use App\Repositories\Dashboard\OfferComplaintsRepository;
use App\Repositories\Subscription\SubscriptionRepository;
use App\Interfaces\Payment\MoyasarPaymentGatewayInterface;
use App\Repositories\Dashboard\OfferSocialMediaRepository;
use App\Interfaces\Dashboard\CategoriesRepositoryInterface;
use App\Interfaces\Offer\OfferComplaintRepositoryInterface;
use App\Interfaces\User\UserSocialMediaRepositoryInterface;
use App\Interfaces\Dashboard\OfferImagesRepositoryInterface;
use App\Interfaces\Dashboard\OfferReportsRepositoryInterface;
use App\Interfaces\Dashboard\PlanFeaturesRepositoryInterface;
use App\Interfaces\Dashboard\UserProfilesRepositoryInterface;
use App\Repositories\Notification\UserNotificationRepository;
use App\Interfaces\Dashboard\SubscriptionsRepositoryInterface;
use App\Interfaces\Dashboard\FavoriteOffersRepositoryInterface;
use App\Interfaces\Dashboard\OfferComplaintsRepositoryInterface;
use App\Interfaces\Subscription\SubscriptionRepositoryInterface;
use App\Interfaces\Dashboard\OfferSocialMediaRepositoryInterface;
use App\Interfaces\Notification\PushNotificationRepositoryInterface;
use App\Interfaces\Notification\UserNotificationRepositoryInterface;
use App\Repositories\Dashboard\PendingProfileVerificationsRepository;
use App\Interfaces\Dashboard\PendingProfileVerificationsRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
        $this->app->bind(UserSocialMediaRepositoryInterface::class, UserSocialMediaRepository::class);
        $this->app->bind(FavoriteOfferInterface::class, FavoriteOfferRepository::class);
        $this->app->bind(SliderRepositoryInterface::class, SliderRepository::class);

        $this->app->bind(OfferReportRepositoryInterface::class, OfferReportRepository::class);
        $this->app->bind(OfferComplaintRepositoryInterface::class, OfferComplaintRepository::class);
        $this->app->bind(ProfileRepositoryInterface::class, ProfileRepository::class);

        // dashboard bindings can be added here
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UsersRepositoryInterface::class, UsersRepository::class);
        $this->app->bind(CategoriesRepositoryInterface::class, CategoriesRepository::class);
        $this->app->bind(OffersRepositoryInterface::class, OffersRepository::class);
        $this->app->bind(OfferImagesRepositoryInterface::class, OfferImagesRepository::class);
        $this->app->bind(OfferSocialMediaRepositoryInterface::class, OfferSocialMediaRepository::class);
        $this->app->bind(SlidersRepositoryInterface::class, SlidersRepository::class);
        // new
        $this->app->bind(PlansRepositoryInterface::class, PlansRepository::class);
        $this->app->bind(PlanFeaturesRepositoryInterface::class, PlanFeaturesRepository::class);
        $this->app->bind(SubscriptionsRepositoryInterface::class, SubscriptionsRepository::class);

        $this->app->bind(UserProfilesRepositoryInterface::class, UserProfilesRepository::class);

        $this->app->bind(FavoriteOffersRepositoryInterface::class, FavoriteOffersRepository::class);

        $this->app->bind(OfferReportsRepositoryInterface::class, OfferReportsRepository::class);
        $this->app->bind(OfferComplaintsRepositoryInterface::class, OfferComplaintsRepository::class);

        $this->app->bind(PendingProfileVerificationsRepositoryInterface::class, PendingProfileVerificationsRepository::class);


        $this->app->bind(UserNotificationRepositoryInterface::class, UserNotificationRepository::class);
        $this->app->bind(PushNotificationRepositoryInterface::class, PushNotificationRepository::class);

        $this->app->bind(PaymentGatewayInterface::class, GeideaPaymentService::class);
        $this->app->bind(MoyasarPaymentGatewayInterface::class, MoyasarPaymentService::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(PaymentsRepositoryInterface::class, PaymentsRepository::class);
    }
// end dashboard bindings


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
