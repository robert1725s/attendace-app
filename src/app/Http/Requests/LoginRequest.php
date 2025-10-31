<?php

namespace App\Http\Requests;

use App\Models\User;
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
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            'password.required' => 'パスワードを入力してください',
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
            // 管理者ログインからは管理者のみ、一般ログインからは一般ユーザのみログイン可能にする
            if (!$validator->errors()->has('email') && !$validator->errors()->has('password')) {
                $user = User::where('email', $this->email)->first();

                if ($user) {
                    // login_typeから期待される管理者フラグを判定
                    $expectedIsAdmin = $this->login_type === 'admin';

                    // DBのis_adminと期待値が一致しない場合はエラー
                    if ($user->is_admin !== $expectedIsAdmin) {
                        $validator->errors()->add('email', 'ログイン情報が正しくありません');
                    }
                }
            }
        });
    }
}
