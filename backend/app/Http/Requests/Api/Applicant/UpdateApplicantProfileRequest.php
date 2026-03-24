<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Applicant;

use App\Enums\ProfilePrivacyLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class UpdateApplicantProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['nullable', 'string', 'max:255'],
            'university_name' => ['nullable', 'string', 'max:255'],
            'course' => ['nullable', 'integer', 'min:1', 'max:10'],
            'graduation_year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
            'about' => ['nullable', 'string', 'max:5000'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'privacy_level' => ['nullable', new Enum(ProfilePrivacyLevel::class)],
            'preferred_work_formats' => ['nullable', 'array'],
            'preferred_work_formats.*' => ['string', 'max:50'],
            'preferred_cities' => ['nullable', 'array'],
            'preferred_cities.*' => ['string', 'max:100'],
        ];
    }
}
