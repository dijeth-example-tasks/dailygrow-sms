<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\Fortify\CreateNewUser;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user {name?} {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name') ?: $this->ask('Name');
        $email = $this->argument('email') ?: $this->ask('Email');
        $password = $this->secret('Password');

        (new CreateNewUser)->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);
    }
}
