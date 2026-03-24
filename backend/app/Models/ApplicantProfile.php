<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProfileModerationStatus;
use App\Enums\ProfilePrivacyLevel;
use Database\Factories\ApplicantProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $full_name
 * @property string|null $university_name
 * @property int|null $course
 * @property int|null $graduation_year
 * @property string|null $about
 * @property string|null $resume_file_path
 * @property string|null $portfolio_url
 * @property string|null $github_url
 * @property array<int, string>|null $preferred_work_formats
 * @property array<int, string>|null $preferred_cities
 * @property int $profile_views_count
 * @property ProfilePrivacyLevel|string $privacy_level
 * @property ProfileModerationStatus|string $moderation_status
 * @property string|null $moderation_comment
 */
final class ApplicantProfile extends Model
{
    /** @use HasFactory<ApplicantProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'university_name',
        'course',
        'graduation_year',
        'about',
        'resume_file_path',
        'portfolio_url',
        'github_url',
        'privacy_level',
        'preferred_work_formats',
        'preferred_cities',
        'profile_views_count',
        'moderation_status',
        'moderation_comment',
    ];

    protected function casts(): array
    {
        return [
            'privacy_level' => ProfilePrivacyLevel::class,
            'moderation_status' => ProfileModerationStatus::class,
            'preferred_work_formats' => 'array',
            'preferred_cities' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
