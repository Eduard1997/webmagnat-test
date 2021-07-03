<?php

use Illuminate\Database\Seeder;
use App\PollOptions;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Good'],
            ['name' => 'Fair'],
            ['name' => 'Neutral'],
            ['name' => 'Bad']
        ];

        PollOptions::insert($data);
    }
}
