<?php

namespace App\Listeners;

use App\Enums\AchievementType;
use App\Events\LessonWatched;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AwardLessonWatchedAchievement
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
    public function handle(LessonWatched $event): void
    {
        $user = $event->user;
        $lessonsWatched = $user->watched()->count();

        $currentAchievement = Achievement::where('type', AchievementType::LESSON)
            ->where('goal',  $lessonsWatched)
            ->first();
        if(isset($currentAchievement)){
            $user->achievements()->sync([$currentAchievement->id => ['created_at' => now(), 'updated_at' => now()]]);
        }
        
        
    }
}
