<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Task;
use App\Models\TaskRun;
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

    public function checkTask(Task $task, CarbonImmutable $controlDate = null): bool
    {
        $controlDate = $controlDate ?: nowTZ();
        switch ($task->type) {
            case 'once':
                return $this->checkOnce($controlDate, $task->time ? nowImmutable()->setTimestamp($task->time) : null);
            case  'daily':
                return $this->checkDaily($controlDate, $task->time);
            case  'weekly':
                return $this->checkWeekly($controlDate, $task->time);
            case  'monthly':
                return $this->checkMonthly($controlDate, $task->time);
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
        $taskDate = nowTZ();

        Task::with('segment.clients')->whereActive(true)->get()->each(function (Task $task) use ($taskDate) {
            info('TaskRunner: ' . $task->type);
            if (!$this->checkTask($task, $taskDate)) {
                info('Task must not run');
                return;
            }

            $messages = $this->getMessages($task);
            info('Messages count ' . $messages->count());
            if (!$messages->count()) {
                return;
            }

            $phones = $task->segment->clients->pluck('phone');
            $errors = $this->smsService->send($phones, $task->text);
            info('Errors: ' . $errors->count());

            $taskRun = new TaskRun([
                'messages_count' => $task->segment->clients->count(),
                'errors_count' => $errors->count(),
                'date' => $taskDate,
                'task_id' => $task->id,
            ]);

            $taskRun->save();

            if ($task->type === 'once') {
                $task->active = false;
                $task->save();
            }
        });
    }
}
