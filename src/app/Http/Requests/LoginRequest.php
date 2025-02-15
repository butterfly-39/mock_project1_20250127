<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

class LoginRequest extends FortifyLoginRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.exists' => 'ログイン情報が登録されていません',
            'password.required' => 'パスワードを入力してください',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $email = $this->input('email');
        $password = $this->input('password');

        if ($email && $password && \App\Models\User::where('email', $email)->exists()) {
            $validator->errors()->add('email', 'ログイン情報が登録されていません');
        }

        parent::failedValidation($validator);
    }
}
