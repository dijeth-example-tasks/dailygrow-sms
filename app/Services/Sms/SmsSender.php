<?php

namespace App\Services\Sms;

use Illuminate\Support\Collection;

interface SmsSender
{
    public function send(string $id, int $startHour, int $endHour, Collection $phones, string $text): SmsServiceResponse;
}
