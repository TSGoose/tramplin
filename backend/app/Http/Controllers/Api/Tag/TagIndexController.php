<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Tag;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class TagIndexController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        return TagResource::collection(
            Tag::query()->orderBy('name')->get()
        );
    }
}
