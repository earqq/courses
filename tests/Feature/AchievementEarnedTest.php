<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Badge;
use App\Models\Achievement;
use App\Enums\AchievementType;
use Database\Seeders\AchievementSeeder;
use App\Listeners\AwardLessonWatchedAchievement;
use App\Events\AchievementEarned;
use Database\Seeders\BadgeSeeder;
use Mockery;


class AchievementEarnedTest extends TestCase
{   
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Seed the necessary data
        $this->seed(AchievementSeeder::class);
        $this->seed(BadgeSeeder::class);
    }
    /**
     * Testing if an badge is created  when a achievements is earned
     */
    public function test_it_awards_badge_on_earned_achievements(): void
    {
        $user = User::factory()->create();
        $goals = [4,8,10];
        $goal = $goals[array_rand($goals)];
        $achievements = Achievement::take($goal)->get();
        foreach($achievements as $achievement){
            $user->achievements()->attach($achievement);
            event(new AchievementEarned($achievement->name, $user));
        }
        $badge = Badge::where('goal',$goal)->first();
        $this->assertTrue($user->badges->contains($badge));
    }
    /**
     * Testing if an badge is not created  when a achievements is not earned
     */
    public function test_it_no_awards_badge_on_wrong_earned_achievements(): void
    {
        $user = User::factory()->create();
        $goals = [3];
        $goal = $goals[array_rand($goals)];
        $achievements = Achievement::take($goal)->get();
        foreach($achievements as $achievement){
            $user->achievements()->attach($achievement);
            event(new AchievementEarned($achievement->name, $user));
        }
        $badge = Badge::where('goal',$goal)->first();
        $this->assertFalse($user->badges->contains($badge));
    }
    /**
     * Testing if a same user don´t register the same badge twice
     */
    public function test_user_does_not_receive_duplicate_badge(): void
    {
        $user = User::factory()->create();
        $achievements = Achievement::take(4)->get(); 
        foreach($achievements as $achievement){
            $user->achievements()->attach($achievement);
            event(new AchievementEarned($achievement->name, $user));
        }
        $badge = Badge::where('goal', 4)->first();
        $user->badges()->syncWithoutDetaching([$badge->id]);

        event(new AchievementEarned('testing', $user));

        $badgesCount = $user->badges()->count();
        $this->assertEquals(1, $badgesCount, "The user should not receive a duplicate badge.");
    }

}
