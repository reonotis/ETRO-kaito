<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->createMany([
            [
                'name' => '藤澤',
                'email' => 'fujisawa@reonotis.jp',
                'password' => 'password',
            ], [
                'name' => '谷畑',
                'email' => 'info@tww-event.com',
                'password' => 'yabata#abc123',
            ], [
                'name' => '大村 沙織',
                'email' => 'so@fivew.jp',
                'password' => 'omura#abc123',
            ]
        ]);

//        Application::factory(100)->create();
    }
}
