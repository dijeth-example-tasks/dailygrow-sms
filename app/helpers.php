<?php

use Carbon\CarbonImmutable;

if (!function_exists('nowImmutable')) {
    function nowImmutable(): CarbonImmutable
    {
        return now()->toImmutable();
    }
}

if (!function_exists('nowTZ')) {
    function nowTZ(): CarbonImmutable
    {
        return now()->setTimezone(env('APP_TIMEZONE', config('app.timezone')))->toImmutable();
    }
}

if (!function_exists('getApiResponse')) {
    function getApiResponse(array $body): array
    {
        return [
            'success' => true,
            'data' => $body,
        ];
    }
}
