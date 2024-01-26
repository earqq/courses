<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'id' => $this->id,
            'name' => $this->name,
            // 'goal' => $this->goal,
            // 'type' => $this->type->label(),
            // 'earned_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
