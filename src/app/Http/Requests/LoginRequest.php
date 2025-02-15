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
            'login' => 'required|exists:users,email,name|email',
            'password' => 'required|min:8',
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
            $user = \App\Models\User::where('email', $login)
                ->orWhere('name', $login)
                ->first();

            if (!$user) {
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
