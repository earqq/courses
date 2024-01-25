<?php

namespace App\Models;

use App\Enums\BadgeLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;
    protected $casts = [
        'level' => BadgeLevel::class,
    ];
}
