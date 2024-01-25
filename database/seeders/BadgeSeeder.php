<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badge = new Badge();
        $badge->name = 'Beginner';
        $badge->goal = 0;
        $badge->save();

        $badge = new Badge();
        $badge->name = 'Intermediate';
        $badge->goal = 4;
        $badge->save();

        $badge = new Badge();
        $badge->name = 'Advanced';
        $badge->goal = 8;
        $badge->save();

        $badge = new Badge();
        $badge->name = 'Master';
        $badge->goal = 10;
        $badge->save();
      
    }
}
