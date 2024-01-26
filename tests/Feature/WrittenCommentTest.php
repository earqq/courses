<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\User;
use App\Models\Achievement;
use App\Enums\AchievementType;
use App\Events\CommentWritten;
use Database\Seeders\AchievementSeeder;
use App\Listeners\AwardCommentWrittenAchievement;
use Mockery;


class WrittenCommentTest extends TestCase
{   
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Seed the necessary data
        $this->seed(AchievementSeeder::class);
    }
    /**
     * Testing if an achievement is created  when a comment is watched
     */
    public function test_it_awards_achievement_on_written_comment(): void
    {
        $user = User::factory()->create();
        $goals = [3,5,10,20];
        $goal = $goals[array_rand($goals)];
        foreach(range(1,$goal) as $i){
            $comment = Comment::factory()->create([
                'user_id' => $user->id,
            ]);
        }
        event(new CommentWritten($comment));
        $achievement = Achievement::where('type', AchievementType::COMMENT)->where('goal',$goal)->first();
        $this->assertTrue($user->achievements->contains($achievement));
    }
    /**
     * Testing if the listener for comment watched event is working
     */
    public function test_it_responds_to_written_comment_event()
    {
        $mock = Mockery::mock(AwardCommentWrittenAchievement::class);
        $mock->shouldReceive('handle')->once();

        $this->app->instance(AwardCommentWrittenAchievement::class, $mock);

        $user = User::factory()->create();
        $comment = Comment::factory()->create();
        event(new CommentWritten($comment));

        $mock->shouldHaveReceived('handle');
    }
    /**
     * Testing if not award duplicate achievements
     */
    public function test_it_does_not_award_duplicate_achievements_with_written_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();
        $achievement = Achievement::where('type', AchievementType::COMMENT)->where('goal',1)->first();
        $user->achievements()->attach([$achievement->id => ['created_at' => now(), 'updated_at' => now()]]);
        event(new CommentWritten($comment));
        $user->load('achievements');
        $this->assertCount(1, $user->achievements, 'Duplicate achievements were awarded.');
    }
}
