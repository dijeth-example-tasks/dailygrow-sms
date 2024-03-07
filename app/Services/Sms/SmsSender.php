<?php

namespace App\Services\Sms;

use Illuminate\Support\Collection;

interface SmsSender
{
    public function send(Collection $phones, string $text): Collection;
}
