<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource($this->whenLoaded('user')), 
            'rating' => $this->rating,
            'review' => $this->review,
           
        ];
    }
}
