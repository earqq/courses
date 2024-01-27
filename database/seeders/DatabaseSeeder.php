<?php

namespace Database\Seeders;

use App\Enums\AchievementType;
use App\Models\Achievement;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Badge;
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

        $this->call([
            AchievementSeeder::class,
            BadgeSeeder::class,
        ]);

        $user = User::factory()->create();

        $badge = Badge::where('goal',0)->first();
        $user->badges()->attach([$badge->id => ['created_at' => now(), 'updated_at' => now()]]);

    }
}
