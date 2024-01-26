<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Enums\AchievementType;
use App\Models\Achievement;
use App\Events\AchievementEarned;

class AwardCommentWrittenAchievement
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
    public function handle(CommentWritten $event): void
    {
        $user = $event->comment->user;
        $commentsWritten = $user->comments()->count();
        $currentAchievement = Achievement::where('type', AchievementType::COMMENT)
            ->where('goal',  $commentsWritten)
            ->first();

        if(isset($currentAchievement)){
            $hasAchievement = $user->achievements()->where('achievement_id', $currentAchievement->id)->exists();

            if (!$hasAchievement) {
                $user->achievements()->attach([$currentAchievement->id => ['created_at' => now(), 'updated_at' => now()]]);
                event(new AchievementEarned($currentAchievement->name, $user));
            }
        }
        
    }
}
