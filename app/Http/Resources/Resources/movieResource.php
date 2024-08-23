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
            'title'        => $this->title,
            'director'       => $this->director,
            'gener'    => $this->gener,
            'release_year' => $this->release_year,
            'description'     => $this->description
        ];
    }
}
