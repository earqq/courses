<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\AchievementSeeder;
use App\Models\Badge;
use Database\Seeders\BadgeSeeder;

class ExampleTest extends TestCase
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
}
