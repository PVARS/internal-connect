<?php

namespace App\Http\Requests;

use App\Utils\Constants;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VerifyUserRequest extends FormRequest
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
            'token' => ['required', 'string', 'max:'.Constants::VERIFY_USER_TOKEN_MAX_LENGTH],
            'password' => ['required', 'string', 'min:'.Constants::PASSWORD_MIN_LENGTH, 'max:'.Constants::PASSWORD_MAX_LENGTH],
        ];
    }
}
