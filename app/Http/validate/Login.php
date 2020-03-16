<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/17
 * Time: 14:42
 */

namespace App\Http\validate;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Login extends common
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required',
          //  'yzm' => 'required|captcha',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => '请输入用户名',
            'password.required' => '请输入密码',
            'yzm.required' => '验证码不能为空',
            'yzm.captcha' => '验证码不正确！',
        ];
    }

}