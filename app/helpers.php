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
