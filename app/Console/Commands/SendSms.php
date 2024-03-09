<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TaskRunnerService;

class SendSms extends Command
{
    public function __construct(protected TaskRunnerService $smsService)
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs Sms Task Runner';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->smsService->run();
    }
}
