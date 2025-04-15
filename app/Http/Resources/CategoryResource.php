<?php 


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,

            'translations' => $this->translations->mapWithKeys(function ($item) {
                return [
                    $item->locale => [
                        'title' => $item->title,
                        'description' => $item->description,
                    ]
                ];
            }),

            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}

