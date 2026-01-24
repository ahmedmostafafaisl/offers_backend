<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Payment\GeideaPaymentService;
use App\Interfaces\Payment\PaymentGatewayInterface;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

        $this->app->bind(PaymentGatewayInterface::class, GeideaPaymentService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
