<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Задачи по рассылке
 */

/**
 * @property string type
 *  'once' - одиночная рассылка; 
 *  'daily' - ежедневная; 
 *  'weekly' - раз в неделю; 
 *  'monthly' - раз в месяц;
 *  'birthday' - ко дню рождения
 * 
 * @property integer time - интерпритация значения зависит от поля "type"
 *  'once' - unix time рассылки, 0 - немедленно; 
 *  'daily' - количество часов до рассылки от начала дня; 
 *  'weekly' - количество часов до рассылки от начала недели; 
 *  'monthly' - количество часов до рассылки от начала месяца;
 *  'birthday' - за какое количество часов перед днем рождения;
 *
 * @property boolean active - активна ли задача по рассылке
 * 
 * @property string text - текст рассылки
 * @property string name - название задачи
 * @property string description - описание задачи
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = ['time', 'active', 'type', 'text'];

    protected $casts = ['active' => 'boolean'];

    protected const DEFAULT_TIME = 12;

    public function segment(): BelongsTo
    {
        return $this->belongsTo(Segment::class);
    }

    public function runs(): HasMany
    {
        return $this->hasMany(TaskRun::class)->orderBy('date');
    }

    public function getStartHour(): int
    {
        switch ($this->type) {
            case 'once':
                return $this->time === 0 ? nowTZ()->hour : nowTZ()->setTimestamp($this->time)->hour;
            case  'daily':
            case  'weekly':
            case  'monthly':
                return $this->time % 24;
            case  'birthday':
                return 24 - $this->time % 24;
            default:
                return self::DEFAULT_TIME;
        }
    }

    public function isActiveDay(CarbonImmutable $controlDate): bool
    {
        switch ($this->type) {
            case 'once':
                return $this->time === 0 ? true : nowTZ()->setTimestamp($this->time)->diffInDays($controlDate) === 0;
            case  'daily':
                return true;
            case  'weekly':
                return $controlDate->startOfWeek()->addDays(intdiv($this->time, 24))->diffInDays($controlDate) === 0;
            case  'monthly':
                return $controlDate->startOfMonth()->addDays(intdiv($this->time, 24))->diffInDays($controlDate) === 0;
            case  'birthday':
                return $this->segment->clients
                    ->first(fn (Client $client) => $client->isBirthday($controlDate, $this->time)) !== null;
            default:
                return false;
        }
    }

    public function getClients(CarbonImmutable $controlDate): Collection
    {
        if (!$this->isActiveDay($controlDate)) {
            return collect();
        }

        return $this->type === 'birthday'
            ? $this->segment->clients->filter(fn (Client $client) => $client->isBirthday($controlDate, $this->time))
            : $this->segment->clients;
    }
}
