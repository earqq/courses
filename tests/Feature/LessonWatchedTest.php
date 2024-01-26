<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Achievement;
use App\Enums\AchievementType;
use Database\Seeders\AchievementSeeder;
use App\Listeners\AwardLessonWatchedAchievement;
use Mockery;


class LessonWatchedTest extends TestCase
{   
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Seed the necessary data
        $this->seed(AchievementSeeder::class);
    }
    /**
     * Testing if an achievement is created  when a lesson is watched
     */
    public function test_it_awards_achievement_on_watching_lessons(): void
    {
        $user = User::factory()->create();
        $goals = [1, 5,10,25,50];
        $goal = $goals[array_rand($goals)];
        foreach(range(1,$goal) as $i){
            $lesson = Lesson::factory()->create();
            $user->watched()->attach($lesson,['watched' => true]);
        }
        event(new LessonWatched($lesson, $user));
        $achievement = Achievement::where('type', AchievementType::LESSON)->where('goal',$goal)->first();
        $this->assertTrue($user->achievements->contains($achievement));
    }
    /**
     * Testing if the listener for lesson watched event is working
     */
    public function test_it_responds_to_lesson_watched_event()
    {
        $mock = Mockery::mock(AwardLessonWatchedAchievement::class);
        $mock->shouldReceive('handle')->once();

        $this->app->instance(AwardLessonWatchedAchievement::class, $mock);

        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        event(new LessonWatched($lesson, $user));

        $mock->shouldHaveReceived('handle');
    }
    /**
     * Testing if when there are not achievements seeded, no achievements are awarded
     */
    public function test_it_does_not_award_achievement_if_none_exists_for_current_count()
    {   
        $user = User::factory()->create();
        Achievement::query()->delete();
        $lesson = Lesson::factory()->create();
        $user->watched()->attach($lesson,['watched' => true]);
        event(new LessonWatched($lesson, $user));
        $user->load('achievements');
        $this->assertCount(0, $user->achievements, 'Achievements were awarded when they should not have been.');
    }

    /**
     * Testing if not award duplicate achievements
     */
    public function test_it_does_not_award_duplicate_achievements()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $user->watched()->attach($lesson,['watched' => true]);
        $achievement = Achievement::where('type', AchievementType::LESSON)->where('goal',1)->first();
        $user->achievements()->attach([$achievement->id => ['created_at' => now(), 'updated_at' => now()]]);
        event(new LessonWatched($lesson, $user));
        $user->load('achievements');
        $this->assertCount(1, $user->achievements, 'Duplicate achievements were awarded.');
    }
}
