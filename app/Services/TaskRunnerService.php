<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Task;
use App\Services\Sms\SmsSender;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;


class TaskRunnerService
{
    function __construct(protected SmsSender $smsService)
    {
    }

    public function checkOnce(CarbonImmutable $controlDate, CarbonImmutable|null $actionDate): bool
    {
        if (!$actionDate) {
            return true;
        }

        return abs($controlDate->diffInHours($actionDate)) <= 1;
    }

    public function checkDaily(CarbonImmutable $controlDate, int $hourShift): bool
    {
        return abs($controlDate->hour - $hourShift) <= 1;
    }

    public function checkWeekly(CarbonImmutable $controlDate, int $hourShift): bool
    {
        return abs($controlDate->startOfWeek() - $hourShift) <= 1;
    }

    public function checkMonthly(CarbonImmutable $controlDate, int $hourShift): bool
    {
        return abs($controlDate->startOfMonth() - $hourShift) <= 1;
    }

    public function checkBirthday(CarbonImmutable $controlDate, CarbonImmutable $birthday): bool
    {
        return $controlDate->month === $birthday->month && $controlDate->day === $birthday->day;
    }

    public function checkTask(Task $task): bool
    {
        switch ($task->type) {
            case 'once':
                return $this->checkOnce(nowTZ(), $task->time ? nowImmutable()->setTimestamp($task->time) : null);
            case  'daily':
                return $this->checkDaily(nowTZ(), $task->time);
            case  'weekly':
                return $this->checkWeekly(nowTZ(), $task->time);
            case  'monthly':
                return $this->checkMonthly(nowTZ(), $task->time);
            case  'birthday':
                return true;
            default:
                return false;
        }
    }

    public function getMessages(Task $task): Collection
    {
        $clients = $task->segment->clients;

        if ($task->type === 'birthday') {
            $clients = $clients->filter(fn ($it) => $this->checkBirthday(nowTZ(), $it->birthday));
        }

        return $clients->map(fn ($it) => new Message(['client_id' => $it->id, 'task_id' => $task->id, 'status' => 'sent']));
    }

    public function run()
    {
        Task::with('segment.clients')->whereActive(true)->get()->each(function (Task $task) {
            info('TaskRunner: ' . $task->type);
            if (!$this->checkTask($task)) {
                info('Task must not run');
                return;
            }

            $messages = $this->getMessages($task);
            info('Messages count ' . $messages->count());
            if (!$messages->count()) {
                return;
            }

            $phones = $task->segment->clients->pluck('phone');
            $lastId = Message::max('id');
            // Message::insert($messages->toArray());
            $errors = $this->smsService->send($phones, $task->text);
            info('Errors: ' . $errors->count());
            // Message::where('id', '>', $lastId)->whereNotIn('phone', $errors)->update(['status' => 'received']);
            // Message::where('id', '>', $lastId)->whereIn('phone', $errors)->update(['status' => 'error']);
        });
    }
}
