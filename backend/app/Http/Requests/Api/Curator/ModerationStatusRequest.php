<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Curator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ModerationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'string', Rule::in(['approve', 'reject', 'needs_revision'])],
            'comment' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
