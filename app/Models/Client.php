<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property CarbonImmutable birthday
 */
class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'birthday', 'phone'];

    protected $casts = [
        'birthday' => 'immutable_datetime:Y-m-d'
    ];

    public function segments()
    {
        return $this->belongsToMany(Segment::class, 'segments_clients_pivot')->orderBy('created_at', 'desc');;
    }

    public function isBirthday(CarbonImmutable $controlDate, int $timeShift): bool
    {
        $date = $this->birthday->startOfDay()->shiftTimezone($controlDate->timezone)->subHours($timeShift);
        return $date->day === $controlDate->day && $date->month === $controlDate->month;
    }
}
