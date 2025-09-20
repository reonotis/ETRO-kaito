<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string f_name
 * @property string l_name
 * @property string f_read
 * @property string l_read
 * @property string email
 * @property int sex
 * @property int goal_time
 * @property int shoes_size
 */
class ApplicationFormRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'=> ['required'],
            'address'=> ['required'],
            'tel'=> ['required'],
            'email' => ['required', 'regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', 'confirmed', 'email'],
            'email_confirmation' => ['required', 'regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/'],
            'target_date'=> ['required'],
        ];
    }

    /**
     * エラーメッセージをカスタマイズする
     * @return array string[]
     */
    public function messages(): array
    {
        return [
        ];
    }

    /**
     * 項目名を定義する
     * @return array string[]
     */
    public function attributes(): array
    {
        return [
            'name' => '名前',
            'address' => '居住国',
            'email' => 'メールアドレス',
            'email_confirmation' => 'メールアドレス(確認用)',
            'target_date' => '希望イベント',
        ];
    }
}

