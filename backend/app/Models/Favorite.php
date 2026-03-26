<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FavoriteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Favorite extends Model
{
    /** @use HasFactory<FavoriteFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'opportunity_id',
        'planned_apply_at',
    ];

    protected function casts(): array
    {
        return [
            'planned_apply_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }
}
