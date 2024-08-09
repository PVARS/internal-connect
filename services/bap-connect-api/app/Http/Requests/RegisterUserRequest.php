<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Rules\EmailRule;
use App\Utils\Constants;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:'.Constants::FIRST_NAME_MAX_LENGTH],
            'last_name' => ['required', 'string', 'max:'.Constants::LAST_NAME_MAX_LENGTH],
            'email' => ['required', new EmailRule, 'max:'.Constants::EMAIL_MAX_LENGTH, 'unique:users'],
            'gender' => ['required', 'in:'.Gender::MALE->value.','.Gender::FEMALE->value.','.Gender::OTHER->value],
            'username' => ['required', 'max:'.Constants::USERNAME_MAX_LENGTH, 'unique:users'],
            'birthday' => ['nullable', 'date', 'date_format:'.Constants::DATE_FORMAT_ISO, 'before_or_equal:today'],
            'phone' => ['nullable', 'unique:users', 'max:'.Constants::PHONE_MAX_LENGTH],
            'province' => ['nullable', 'max:'.Constants::PROVINCE_MAX_LENGTH],
            'district' => ['nullable', 'max:'.Constants::DISTRICT_MAX_LENGTH],
            'ward' => ['nullable', 'max:'.Constants::WARD_MAX_LENGTH],
            'address' => ['nullable', 'max:'.Constants::ADDRESS_MAX_LENGTH],
        ];
    }
}
