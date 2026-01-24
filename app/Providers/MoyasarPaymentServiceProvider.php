<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\PaymentGatewayInterface;
use App\Services\Payment\Moyasar\MoyasarPaymentService;
use App\Interfaces\Payment\MoyasarPaymentGatewayInterface;

class MoyasarPaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {




        $this->app->bind(MoyasarPaymentGatewayInterface::class, MoyasarPaymentService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
