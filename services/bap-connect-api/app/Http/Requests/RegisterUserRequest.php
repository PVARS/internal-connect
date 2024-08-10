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
            'first_name' => ['bail', 'required', 'string', 'max:'.Constants::FIRST_NAME_MAX_LENGTH],
            'last_name' => ['bail', 'required', 'string', 'max:'.Constants::LAST_NAME_MAX_LENGTH],
            'email' => ['bail', 'required', new EmailRule, 'unique:users'],
            'gender' => ['bail', 'required', 'in:'.Gender::MALE->value.','.Gender::FEMALE->value.','.Gender::OTHER->value],
            'username' => ['bail', 'required', 'max:'.Constants::USERNAME_MAX_LENGTH, 'unique:users'],
            'birthday' => ['bail', 'nullable', 'date', 'date_format:'.Constants::DATE_FORMAT_ISO, 'before_or_equal:today'],
            'phone' => ['bail', 'nullable', 'unique:users', 'max:'.Constants::PHONE_MAX_LENGTH],
            'province' => ['bail', 'nullable', 'max:'.Constants::PROVINCE_MAX_LENGTH],
            'district' => ['bail', 'nullable', 'max:'.Constants::DISTRICT_MAX_LENGTH],
            'ward' => ['bail', 'nullable', 'max:'.Constants::WARD_MAX_LENGTH],
            'address' => ['bail', 'nullable', 'max:'.Constants::ADDRESS_MAX_LENGTH],
        ];
    }
}
