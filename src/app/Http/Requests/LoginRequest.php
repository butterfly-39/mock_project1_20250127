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
            'login' => ['required', function ($attribute, $value, $fail) {
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return;
                }
                if (preg_match('/^[a-zA-Z0-9_\-\p{Han}\p{Hiragana}\p{Katakana}]+$/u', $value)) {
                    return;
                }
                $fail('ユーザー名またはメールアドレスを入力してください');
            }],
            'password' => 'required',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $login = $this->input('login');
            $password = $this->input('password');

            $user = \App\Models\User::where('email', $login)
                ->orWhere('name', $login)
                ->first();

            if (!$user) {
                $validator->errors()->add('login', 'ログイン情報が登録されていません');
                return;
            }

            if (!password_verify($password, $user->password)) {
                $validator->errors()->add('login', 'ログイン情報が登録されていません');
            }
        });
    }

    public function messages()
    {
        return [
            'login.required' => 'ユーザー名またはメールアドレスを入力してください',
            'password.required' => 'パスワードを入力してください',
        ];
    }
}
