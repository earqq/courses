<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AchievementSeeder;
use Database\Seeders\BadgeSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       Lesson::factory()
            ->count(20)
            ->create();

        User::factory()->create();
        
        $this->call([
            AchievementSeeder::class,
            BadgeSeeder::class,
        ]);
    }
}
