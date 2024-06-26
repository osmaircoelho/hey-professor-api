<?php

namespace App\Http\Resources;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Question $this */
        return [
            'id'         => $this->id,
            'question'   => $this->question,
            'status'     => $this->status,
            'created_by' => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],
            'votes_sum_like'   => $this->votes_sum_like ?: 0,
            'votes_sum_unlike' => $this->votes_sum_unlike ?: 0,
            'created_at'       => $this->created_at->format('Y-m-d h:i:s'),
            'updated_at'       => $this->updated_at->format('Y-m-d h:i:s'),
        ];
    }
}
