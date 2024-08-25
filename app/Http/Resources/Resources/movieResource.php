<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class movieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'release_year' => $this->release_year,
            'director' => $this->director,
            'gener' => $this->gener,
            'ratings' => $this->ratings->map(function ($rating) {
                return [
                    'rating' => $rating->rating,
                    'review' => $rating->review,
                    'user' => [
                        'name' => $rating->user->name,
                        'email' => $rating->user->email,
                    ],
                ];
            }),
        ];
    }
}
