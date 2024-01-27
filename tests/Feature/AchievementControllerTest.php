<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\AchievementSeeder;
use App\Models\Badge;
use Database\Seeders\BadgeSeeder;
use App\Events\AchievementEarned;

class AchievementControllerTest extends TestCase
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
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();
        $badge = Badge::where('goal',0)->first();
        $user->badges()->attach([$badge->id => ['created_at' => now(), 'updated_at' => now()]]);
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);
    }
    /**
     * Testing the response for achievement controller when a new user is created
     */
    public function test_response_for_a_new_user()
    {
        $user = User::factory()->create();
        $badge = Badge::where('goal',0)->first();
        $user->badges()->attach([$badge->id => ['created_at' => now(), 'updated_at' => now()]]);

        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJson([
            'unlocked_achievements' => [],
            'next_available_achievements' => 
            [ 
                "First Lesson watched",
                "5 Lessons watched",
                "10 Lessons watched",
                "25 Lessons watched",
                "50 Lessons watched",
                "First Comment Written",
                "3 Comments Written",
                "5 Comments Written",
                "10 Comments Written",
                "20 Comments Written"
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 4
        ]);
    }
    /**
     * Testing the response for achievement controller  when a user have all achievements earned
     */
    public function test_response_for_user_with_all_achievements_and_badges_earned()
    {
        $user = User::factory()->create();
        $user->achievements()->attach([1,2,3,4,5,6,7,8,9,10,11,12]);
        event(new AchievementEarned('testing', $user));
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJson([
            'unlocked_achievements' => 
            [
                "First Lesson watched",
                "5 Lessons watched",
                "10 Lessons watched",
                "25 Lessons watched",
                "50 Lessons watched",
                "First Comment Written",
                "3 Comments Written",
                "5 Comments Written",
                "10 Comments Written",
                "20 Comments Written"
            ],
            'next_available_achievements' => [],
            'current_badge' => 'Master',
            'next_badge' => 'No more badges',
            'remaining_to_unlock_next_badge' => 0
        ]);
    }
    /**
     * Testing the response for achievement controller  when a user have all badges earned
     */
  
}
