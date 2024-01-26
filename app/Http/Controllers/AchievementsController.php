<?php

namespace App\Http\Controllers;

use App\Enums\AchievementType;
use App\Models\Achievement;
use App\Models\User;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\BadgeResource;
use App\Models\Badge;
class AchievementsController extends Controller
{
    public function index(User $user)
    {   
        $userAllAchievements = $user->achievements()
        ->orderBy('type','asc')
        ->orderBy('goal','asc')
        ->get();

        $userCurrentBadge = $user->badges()->orderBy('badge_user.created_at', 'desc')->first();
        $nextUserBadge = Badge::where('goal', '>', $userCurrentBadge->goal)->first();

        if($userAllAchievements->isEmpty()){
            $allAchievements = Achievement::select('name')->orderBy('type', 'asc')->orderBy('goal', 'asc')->pluck('name');
            return response()->json([
                'unlocked_achievements' => [],
                'next_available_achievements' => $allAchievements,
                'current_badge' => $userCurrentBadge->name,
                'next_badge' => $nextUserBadge->name,
                'remaining_to_unlock_next_badge' => $nextUserBadge->goal
            ]);
        }

        $unlockedAchievementNames = $userAllAchievements->pluck('name');

        $userLessonAchievementsUnlockedIds = $user->achievements()
        ->where('type', AchievementType::LESSON)
        ->pluck('id');

        $nextLessonAchievementToUnlock = Achievement::where('type', AchievementType::LESSON)
        ->whereNotIn('id', $userLessonAchievementsUnlockedIds)
        ->orderBy('goal', 'asc')
        ->first();

        $userCommentAchievementsUnlockedIds = $user->achievements()
        ->where('type', AchievementType::COMMENT)
        ->pluck('id');

        $nextCommentAchievementToUnlock = Achievement::where('type', AchievementType::COMMENT)
        ->whereNotIn('id', $userCommentAchievementsUnlockedIds)
        ->orderBy('goal', 'asc')
        ->first();


        $countUserAchievements = $user->achievements()->count();
        $remainingToUnlockNextBadge = $nextUserBadge->goal - $countUserAchievements;


        return response()->json([
            'unlocked_achievements' => $unlockedAchievementNames,
            'next_available_achievements' => [$nextLessonAchievementToUnlock->name , $nextCommentAchievementToUnlock->name],
            'current_badge' => $userCurrentBadge->name,
            'next_badge' => $nextUserBadge->name,
            'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge
        ]);
    }
}
