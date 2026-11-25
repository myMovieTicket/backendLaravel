<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'duration_minutes' => $this->duration_minutes,
            'genre' => $this->genre,
            'rating' => $this->rating,
            'poster_url' => $this->poster_url,
            'status' => $this->status,
        ];
    }
}
