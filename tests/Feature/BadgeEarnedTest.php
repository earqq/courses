<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Badge;
use Database\Seeders\AchievementSeeder;
use App\Listeners\AssignBadge;
use App\Events\BadgeEarned;
use Database\Seeders\BadgeSeeder;
use Mockery;
use Illuminate\Support\Facades\Event;

class BadgeEarnedTest extends TestCase
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
    public function test_badge_earned_fired_with_correct_payload(): void
    {
        Event::fake();
        $user = User::factory()->create();
        $badge = Badge::where('goal', 4)->first();

        event(new BadgeEarned($badge->name, $user));

        Event::assertDispatched(BadgeEarned::class, function ($event) use ($user, $badge) {
            return $event->badge_name === $badge->name && $event->user->is($user);
        });
    }
   

}
