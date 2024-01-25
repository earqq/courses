<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Achievement;
use App\Enums\AchievementType;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //first we seed the achievements for lessons
        $achievement = new Achievement();
        $achievement->name = 'First Lesson watched';
        $achievement->type = AchievementType::LESSON;
        $achievement->goal = 1;
        $achievement->save();

        $achievement = new Achievement();
        $achievement->name = '5 Lessons watched';
        $achievement->type = AchievementType::LESSON;
        $achievement->goal = 5;
        $achievement->save();

        $achievement = new Achievement();
        $achievement->name = '10 Lessons watched';
        $achievement->type = AchievementType::LESSON;
        $achievement->goal = 10;
        $achievement->save();

        $achievement = new Achievement();
        $achievement->name = '25 Lessons watched';
        $achievement->type = AchievementType::LESSON;
        $achievement->goal = 25;
        $achievement->save();

        $achievement = new Achievement();
        $achievement->name = '50 Lessons watched';
        $achievement->type = AchievementType::LESSON;
        $achievement->goal = 50;
        $achievement->save();

        // after we seed the achievements for comments

        $achievement = new Achievement();
        $achievement->name = 'First Comment Written';
        $achievement->type = AchievementType::COMMENT;
        $achievement->goal = 1;
        $achievement->save();

        $achievement = new Achievement();
        $achievement->name = '3 Comments Written';
        $achievement->type = AchievementType::COMMENT;
        $achievement->goal = 3;
        $achievement->save();

        $achievement = new Achievement();
        $achievement->name = '5 Comments Written';
        $achievement->type = AchievementType::COMMENT;
        $achievement->goal = 5;
        $achievement->save();

        $achievement = new Achievement();
        $achievement->name = '10 Comments Written';
        $achievement->type = AchievementType::COMMENT;
        $achievement->goal = 10;
        $achievement->save();

        $achievement = new Achievement();
        $achievement->name = '20 Comments Written';
        $achievement->type = AchievementType::COMMENT;
        $achievement->goal = 20;
        $achievement->save();
    }
}
