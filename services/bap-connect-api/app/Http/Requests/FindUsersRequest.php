<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Utils\Constants;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FindUsersRequest extends FormRequest
{
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'max:255'],
            'gender' => ['nullable', 'in:'.Gender::MALE->value.','.Gender::FEMALE->value.','.Gender::OTHER->value],
            'username' => ['nullable', 'max:50'],
            'birthday_from' => ['nullable', 'date', 'date_format:'.Constants::DATE_FORMAT_ISO],
            'birthday_to' => ['nullable', 'date', 'date_format:'.Constants::DATE_FORMAT_ISO],
        ];
    }
}
