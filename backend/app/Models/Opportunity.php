<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OpportunityEmploymentType;
use App\Enums\OpportunityLevel;
use App\Enums\OpportunityStatus;
use App\Enums\OpportunityType;
use App\Enums\OpportunityWorkFormat;
use Database\Factories\OpportunityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Opportunity extends Model
{
    /** @use HasFactory<OpportunityFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'short_description',
        'full_description',
        'type',
        'work_format',
        'employment_type',
        'level',
        'city',
        'address',
        'latitude',
        'longitude',
        'is_remote',
        'published_at',
        'expires_at',
        'event_date',
        'salary_from',
        'salary_to',
        'contacts_text',
        'external_url',
        'status',
        'moderation_comment',
    ];

    protected function casts(): array
    {
        return [
            'type' => OpportunityType::class,
            'work_format' => OpportunityWorkFormat::class,
            'employment_type' => OpportunityEmploymentType::class,
            'level' => OpportunityLevel::class,
            'status' => OpportunityStatus::class,
            'is_remote' => 'boolean',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
            'event_date' => 'datetime',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
