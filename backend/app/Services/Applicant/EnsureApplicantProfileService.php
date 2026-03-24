<?php

declare(strict_types=1);

namespace App\Services\Applicant;

use App\Enums\ProfileModerationStatus;
use App\Enums\ProfilePrivacyLevel;
use App\Enums\UserRole;
use App\Models\ApplicantProfile;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

final readonly class EnsureApplicantProfileService
{
    public function handle(User $user): ApplicantProfile
    {
        if ($user->role !== UserRole::Applicant) {
            throw new HttpException(403, 'Профиль соискателя доступен только для роли applicant.');
        }

        /** @var ApplicantProfile */
        return $user->applicantProfile()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $user->display_name,
                'privacy_level' => ProfilePrivacyLevel::PlatformVisible,
                'moderation_status' => ProfileModerationStatus::Unreviewed,
                'preferred_work_formats' => [],
                'preferred_cities' => [],
            ],
        );
    }
}
