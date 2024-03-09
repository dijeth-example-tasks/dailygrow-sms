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

    public function run()
    {
        $taskDate = nowTZ();

        Task::with('segment.clients')->whereActive(true)->get()->each(function (Task $task) use ($taskDate) {
            logger()->info(self::class, $task->toArray());

            $taskClients = $task->getClients($taskDate);

            if (!$taskClients->count()) {
                logger()->info('Task must not run');
                return;
            }

            logger()->info('Task messages count: ' . $taskClients->count());

            /**
             * @var SmsServiceResponse
             */
            $result = $this->smsService->send($task->id, $task->getStartHour(), $task->getStartHour() + 1, $taskClients->pluck('phone'), $task->text);

            $taskRun = new TaskRun([
                'messages_count' => $result->messagesCount,
                'errors_count' => $result->errorMessagesCount,
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
