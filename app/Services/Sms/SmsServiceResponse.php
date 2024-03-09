<?php

namespace App\Services\Sms;

class SmsServiceResponse
{
    public function __construct(
        public string $id,
        public int $messagesCount,
        public int $errorMessagesCount,
    ) {
    }
}
