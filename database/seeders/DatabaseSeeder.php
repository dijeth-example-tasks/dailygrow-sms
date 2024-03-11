<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Client;
use App\Models\Segment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        DB::table('task_runs')->delete();
        DB::table('tasks')->delete();
        DB::table('segments')->delete();
        DB::table('clients')->delete();

        User::create([
            'name' => 'Demo User',
            'email' => 'demo-user@email.com',
            'password' => Hash::make('demo-user'),
        ]);

        $devSegment = Segment::create(['name' => 'Dev segment']);
        $mainSegment = Segment::create(['name' => 'Main segment']);

        $mainClients = [
            [
                "phone" => "79000010000",
                "name" => "Иван Иванов",
                "birthday" => nowTZ()->addDays(7)->subDays(2)->subYears(25)->toDateString(),
            ],
            [
                "phone" => "79000010010",
                "name" => "Алексей Петров",
                "birthday" => nowTZ()->addDays(7)->subDays(1)->subYears(25)->toDateString(),
            ],
            [
                "phone" => "79000010020",
                "name" => "Констанин Жаров",
                "birthday" => nowTZ()->addDays(7)->subDays(0)->subYears(25)->toDateString(),
            ],
            [
                "phone" => "79000010030",
                "name" => "Максим Леонов",
                "birthday" => nowTZ()->addDays(7)->addDays(1)->subYears(25)->toDateString(),
            ],
            [
                "phone" => "79005250538",
                "name" => "Денис Турушев",
                "birthday" => nowTZ()->addDays(7)->addDays(2)->subYears(25)->toDateString(),
            ]
        ];

        $devClients = [
            [
                "phone" => "79261089800",
                "name" => "Дмитрий Орлов",
                "birthday" => nowTZ()->addDays(7)->subDays(0)->subYears(25)->toDateString(),
            ],
            [
                "phone" => "79265217847",
                "name" => "Елена",
                "birthday" => nowTZ()->addDays(7)->subDays(1)->subYears(25)->toDateString(),
            ],
        ];

        Client::factory()->createMany($devClients)->each(fn ($it) => $it->segments()->attach($devSegment->id));
        Client::factory()->createMany($mainClients)->each(fn ($it) => $it->segments()->attach($mainSegment->id));

        $tasks = [
            [
                'time' => 0,
                'active' => true,
                'type' => 'once',
                'text' => 'Once',
                'segment_id' => $devSegment->id,
                'name' => 'Разовая ближайшая рассылка',
                'description' => 'Пример разовой рассылки в ближайший момент'
            ],
            [
                'time' => nowTZ()->addHour()->timestamp,
                'active' => true,
                'type' => 'once',
                'text' => 'Once',
                'segment_id' => $devSegment->id,
                'name' => 'Разовая рассылка',
                'description' => 'Пример разовой рассылки в определенный день и время'
            ],
            [
                'time' => 24 * 7 - nowTZ()->hour - 1,
                'active' => true,
                'type' => 'birthday',
                'text' => 'Birthday',
                'segment_id' => $devSegment->id,
                'name' => 'Рассылка перед днем рождения',
                'description' => 'Пример рассылки за 7 дней перед днем рождения'
            ],
            [
                'time' => nowTZ()->hour + 1,
                'active' => true,
                'type' => 'daily',
                'text' => 'Daily',
                'segment_id' => $devSegment->id,
                'name' => 'Ежедневная рассылка',
                'description' => 'Пример ежедневной рассылки в определенное время'
            ],
        ];

        Task::factory()->createMany($tasks);
    }
}
