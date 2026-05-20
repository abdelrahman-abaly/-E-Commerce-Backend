<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'rating'     => $this->rating,
            'title'      => $this->title,
            'comment'    => $this->comment,
            'user'       => $this->whenLoaded('user', fn () => [
                'name'   => $this->user->name,
                'avatar' => $this->user->avatar,
            ]),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
