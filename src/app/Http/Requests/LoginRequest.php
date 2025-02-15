<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

class LoginRequest extends FortifyLoginRequest
{
    /**
  * Determine if the user is authorized to make this request.
  *
  * @return bool
  */
    public function authorize()
    {
    return true;
    }

    /**
  * Get the validation rules that apply to the request.
  *
  * @return array
  */
    public function rules()
    {
        return [
            'login' => 'required_without:name|required_without:email|email',
            'password' => 'required',
        ];
    }
}
