<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Opportunity;

use Illuminate\Foundation\Http\FormRequest;

final class IndexOpportunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'work_format' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'tag' => ['nullable', 'string', 'max:100'],
        ];
    }
}
