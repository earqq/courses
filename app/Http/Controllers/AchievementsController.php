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
        //get earned achievements
        $earnedAchievementNames = $user->achievements()
        ->orderBy('type','asc')
        ->orderBy('goal','asc')
        ->pluck('name');

        //get current and next badge
        $userCurrentBadge = $user->badges()->orderBy('badge_user.created_at', 'desc')->first();
        $nextUserBadge = Badge::where('goal', '>', $userCurrentBadge->goal)->first();
        $nextBadgeName = $nextUserBadge ? $nextUserBadge->name : 'No more badges';
        $countUserAchievements = $user->achievements()->count();
        $remainingToUnlockNextBadge = $nextUserBadge ? $nextUserBadge->goal - $countUserAchievements : 0;

        // get next available achievements
        $nextLessonAchievementToUnlockName = $this->getNextAchievements(AchievementType::LESSON, $user);
        $nextCommentAchievementToUnlockName = $this->getNextAchievements(AchievementType::COMMENT, $user);
        $nextAchievements = [];
        if(isset($nextLessonAchievementToUnlockName))
            $nextAchievements = $this->mergeNextAchievements($nextAchievements, $nextLessonAchievementToUnlockName);
        if(isset($nextCommentAchievementToUnlockName))
            $nextAchievements = $this->mergeNextAchievements($nextAchievements, $nextCommentAchievementToUnlockName);
        

        return response()->json([
            'unlocked_achievements' => $earnedAchievementNames,
            'next_available_achievements' => $nextAchievements,
            'current_badge' => $userCurrentBadge->name ?? 'No badge',
            'next_badge' => $nextBadgeName,
            'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge
        ]);
    }
    private function getNextAchievements(AchievementType $type,$user){
        $userAchievementsUnlockedIds = $user->achievements()
        ->where('type', $type)
        ->pluck('id');

        return Achievement::where('type', $type)
        ->whereNotIn('id', $userAchievementsUnlockedIds)
        ->orderBy('goal', 'asc')
        ->pluck('name')
        ->toArray();
    }
    private function mergeNextAchievements($array, $nextAchievements){
        if(isset($nextAchievements)){
            $array = array_merge($array, $nextAchievements);
        }
        return $array;
    }
}
