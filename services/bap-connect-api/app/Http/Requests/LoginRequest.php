<?php

namespace App\Http\Requests;

use App\Utils\Constants;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:'.Constants::EMAIL_MAX_LENGTH],
            'password' => ['required', 'string', 'min:'.Constants::PASSWORD_MIN_LENGTH, 'max:'.Constants::PASSWORD_MAX_LENGTH],
        ];
    }
}
