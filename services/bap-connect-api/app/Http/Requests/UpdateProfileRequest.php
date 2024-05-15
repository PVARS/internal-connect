<?php

namespace App\Http\Requests;

use App\Utils\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'birthday' => ['nullable', 'date', 'date_format:'.Constants::DATE_FORMAT_ISO, 'before_or_equal:today'],
            'phone' => ['nullable', Rule::unique('users')->ignore($this->user()->id), 'max:'.Constants::PHONE_MAX_LENGTH],
            'province' => ['nullable', 'max:'.Constants::PROVINCE_MAX_LENGTH],
            'district' => ['nullable', 'max:'.Constants::DISTRICT_MAX_LENGTH],
            'ward' => ['nullable', 'max:'.Constants::WARD_MAX_LENGTH],
            'address' => ['nullable', 'max:'.Constants::ADDRESS_MAX_LENGTH],
        ];
    }
}
