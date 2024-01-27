<?php

namespace App\Listeners;

use App\Events\BadgeEarned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Badge;
use App\Events\AchievementEarned;
class AssignBadge
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AchievementEarned $event): void
    {   
        $user = $event->user;
        $achievementsEarned = $user->achievements()->count();
        $currentBadge = Badge::where('goal',  $achievementsEarned)
        ->first();

        if(isset($currentBadge)){
            $hasBadge = $user->badges()->where('badge_id', $currentBadge->id)->exists();
            if (!$hasBadge) {
                $user->badges()->attach([$currentBadge->id => ['created_at' => now(), 'updated_at' => now()]]);
                event(new BadgeEarned($currentBadge->name, $user));
            }
        }
    }
}
