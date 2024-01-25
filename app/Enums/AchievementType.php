<?php

namespace App\Enums;

enum AchievementType: int {
    case LESSON = 1;
    case COMMENT = 2;

    public function label(): string {
        return match($this) {
            self::LESSON => 'Lesson',
            self::COMMENT => 'Comment',
        };
    }
}