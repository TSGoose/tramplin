<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
final class CurrentUserResource extends JsonResource
{
    /**
     * @return array<string, int|string>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'display_name' => $this->resource->display_name,
            'email' => $this->resource->email,
            'role' => $this->resource->role->value,
        ];
    }
}
