<?php

namespace App\Services\Sms;

use Illuminate\Support\Collection;

class MockSmsSender implements SmsSender
{
    public function send(Collection $phones, string $text): Collection
    {
        $errorPhones = $phones->shuffle()->take(rand(1, 5));

        $data = [
            'login' => env('SMS_LOGIN'),
            'psw' => env('SMS_PASSWORD'),
            'phones' => $phones->join(','),
            'mes' => $text,
            'err' => 1,
        ];

        info(env('SMS_URL'), $data);

        return $errorPhones;
    }
}
