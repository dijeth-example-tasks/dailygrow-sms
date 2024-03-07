<?php

namespace App\Providers;

use App\Services\Sms\MockSmsSender;
use App\Services\Sms\SmscRuSender;
use App\Services\Sms\SmsSender;
use Illuminate\Support\ServiceProvider;

class SmsSenderServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        info(env('MOCK_SMS_SERVICE'));
        $smsService = env('MOCK_SMS_SERVICE') ? MockSmsSender::class : SmscRuSender::class;
        $this->app->bind(SmsSender::class, $smsService);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
