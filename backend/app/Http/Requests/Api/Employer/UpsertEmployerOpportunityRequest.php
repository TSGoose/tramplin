<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Employer;

use App\Enums\OpportunityEmploymentType;
use App\Enums\OpportunityLevel;
use App\Enums\OpportunityType;
use App\Enums\OpportunityWorkFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class UpsertEmployerOpportunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'full_description' => ['required', 'string', 'max:15000'],
            'type' => ['required', new Enum(OpportunityType::class)],
            'work_format' => ['required', new Enum(OpportunityWorkFormat::class)],
            'employment_type' => ['nullable', new Enum(OpportunityEmploymentType::class)],
            'level' => ['nullable', new Enum(OpportunityLevel::class)],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'is_remote' => ['required', 'boolean'],
            'expires_at' => ['nullable', 'date'],
            'event_date' => ['nullable', 'date'],
            'salary_from' => ['nullable', 'integer', 'min:0'],
            'salary_to' => ['nullable', 'integer', 'min:0'],
            'contacts_text' => ['nullable', 'string', 'max:1000'],
            'external_url' => ['nullable', 'url', 'max:255'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['integer', 'exists:tags,id'],
        ];
    }
}
