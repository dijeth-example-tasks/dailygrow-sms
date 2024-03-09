<?php

namespace App\Services\Sms;

use Illuminate\Support\Collection;
use GuzzleHttp\{Client, RequestOptions};

use function Laravel\Prompts\error;

class SmscRuSender implements SmsSender
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    protected function request(array $body): object
    {
        $body['login'] = config('services.sms.login');
        $body['psw'] = config('services.sms.password');
        $body['tz'] = 0;
        $body['op'] = 1;
        $body['fmt'] = 3;

        logger()->info(self::class, $body);

        $response = $this->client->post(config('services.sms.url'), [RequestOptions::JSON => $body]);

        return json_decode($response->getBody()->getContents(), false);
    }

    public function send(string $id, int $startHour, int $endHour, Collection $phones, string $text): SmsServiceResponse
    {
        $response = $this->request([
            'id' => $id,
            'time' => $startHour . '-' . $endHour,
            'phones' => $phones->join(','),
            'mes' => $text
        ]);

        if (property_exists($response, 'error')) {
            logger()->error(self::class, (array) $response);
            return new SmsServiceResponse($id, $phones->count(), $phones->count());
        }

        $smsServiceResponse = new SmsServiceResponse($id, $phones->count(), $phones->count() - $response->cnt);
        logger()->info(self::class, (array) $smsServiceResponse);

        return $smsServiceResponse;
    }
}
