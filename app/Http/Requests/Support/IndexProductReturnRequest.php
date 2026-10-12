<?php

namespace App\Http\Requests\Support;

use App\Enums\ReturnCategory;
use App\Enums\ReturnPriority;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexProductReturnRequest extends FormRequest
{
    /**
     * The route to redirect to if validation fails.
     *
     * @var string
     */
    protected $redirectRoute = 'support.product-returns.index';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => ['nullable', Rule::enum(ReturnCategory::class)],
            'priority' => ['nullable', Rule::enum(ReturnPriority::class)],
            'requires_human_review' => ['nullable', 'boolean'],
        ];
    }
}
