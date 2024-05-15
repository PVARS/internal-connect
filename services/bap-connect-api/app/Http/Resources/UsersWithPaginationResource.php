<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersWithPaginationResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => UserResource::collection($this->resource['data']),
            'meta' => [
                'next_page_token' => $this->resource['meta']['next_page_token'],
                'previous_page_token' => $this->resource['meta']['previous_page_token'],
            ],
        ];
    }
}
