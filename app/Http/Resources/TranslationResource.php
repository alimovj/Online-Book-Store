<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationResource extends JsonResource
{
    /**
     * Tarjima modelini JSON formatga aylantirish
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'key'        => $this->key,
            'value'      => $this->value,
            'locale'     => $this->locale,
            'is_active'  => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
