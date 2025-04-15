<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'author' => $this->author,
            'price' => $this->price,

            'translations' => $this->translations->mapWithKeys(function ($item) {
                return [
                    $item->locale => [
                        'title' => $item->title,
                        'description' => $item->description,
                    ]
                ];
            }),

            'categories' => $this->whenLoaded('categories', fn () => $this->categories->pluck('name')),
            'image_url' => $this->whenLoaded('images', fn () => optional($this->images->first())->url),

            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
