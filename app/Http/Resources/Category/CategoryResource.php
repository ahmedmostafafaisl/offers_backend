<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,

            // ✅ Correct storage path
            'image' => $this->image
                ? asset('storage/' . $this->image)
                : null,

            'created_at' => $this->created_at
                ? $this->created_at->toDateTimeString()
                : null,

            'updated_at' => $this->updated_at
                ? $this->updated_at->toDateTimeString()
                : null,
        ];
    }
}
