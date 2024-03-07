<?php

namespace App\Services\Sms;

use Illuminate\Support\Collection;

class SmscRuSender implements SmsSender
{
    public function send(Collection $phones, string $text): Collection
    {

        info('smsc.ru');

        return collect();
    }
}
