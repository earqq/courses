<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\AchievementType;

class Achievement extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => AchievementType::class,
    ];
    
}
