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

        $allAchievements = Achievement::select('name')->orderBy('type', 'asc')->orderBy('goal', 'asc')->pluck('name');

        // Check if nextUserBadge exists
        $countUserAchievements = $user->achievements()->count();
        $nextBadgeName = $nextUserBadge ? $nextUserBadge->name : 'No more badges';
        $remainingToUnlockNextBadge = $nextUserBadge ? $nextUserBadge->goal - $countUserAchievements : 0;
        

        if($userAllAchievements->isEmpty()){
            $allAchievements = Achievement::select('name')->orderBy('type', 'asc')->orderBy('goal', 'asc')->pluck('name');
            return response()->json([
                'unlocked_achievements' => [],
                'next_available_achievements' => $allAchievements,
                'current_badge' => $userCurrentBadge->name,
                'next_badge' => $nextBadgeName,
                'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge
            ]);
        }

        $unlockedAchievementNames = $userAllAchievements->pluck('name');

        $userLessonAchievementsUnlockedIds = $user->achievements()
        ->where('type', AchievementType::LESSON)
        ->pluck('id');

        $nextLessonAchievementToUnlockName = Achievement::where('type', AchievementType::LESSON)
        ->whereNotIn('id', $userLessonAchievementsUnlockedIds)
        ->orderBy('goal', 'asc')
        ->value('name');

        $userCommentAchievementsUnlockedIds = $user->achievements()
        ->where('type', AchievementType::COMMENT)
        ->pluck('id');

        $nextCommentAchievementToUnlockName = Achievement::where('type', AchievementType::COMMENT)
        ->whereNotIn('id', $userCommentAchievementsUnlockedIds)
        ->orderBy('goal', 'asc')
        ->value('name');

        $nextAchievements = [];
        if(isset($nextLessonAchievementToUnlockName)){
            array_push($nextAchievements, $nextLessonAchievementToUnlockName);
        }
        if(isset($nextCommentAchievementToUnlockName)){
            array_push($nextAchievements, $nextCommentAchievementToUnlockName);
        }

       

        return response()->json([
            'unlocked_achievements' => $unlockedAchievementNames,
            'next_available_achievements' => $nextAchievements,
            'current_badge' => $userCurrentBadge->name,
            'next_badge' => $nextBadgeName,
            'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge
        ]);
    }
}
