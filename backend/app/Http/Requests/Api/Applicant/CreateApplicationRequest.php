<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Applicant;

use Illuminate\Foundation\Http\FormRequest;

final class CreateApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'opportunity_id' => ['required', 'integer', 'exists:opportunities,id'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
