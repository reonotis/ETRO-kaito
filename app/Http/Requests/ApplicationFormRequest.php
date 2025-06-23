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
            'sei'=> ['required'],
            'mei'=> ['required'],
            'sei_kana'=>  ['required', 'regex:/^[ァ-ヶー]+$/u'],
            'mei_kana'=>  ['required', 'regex:/^[ァ-ヶー]+$/u'],
            'sex' => ['required', 'in:1,2,3,4'],
            'age' => ['required'],
            'zip21'=> ['required', 'size:3'],
            'zip22'=> ['required', 'size:4'],
            'pref21'=> ['required'],
            'address21'=> ['required'],
            'street21'=> ['required'],
            'tel'=> ['required', 'regex:/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/'],
            'email' => ['required', 'regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', 'confirmed', 'email'],
            'email_confirmation' => ['required', 'regex:/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/'],
            'term'=> ['required'],
        ];
    }

    /**
     * エラーメッセージをカスタマイズする
     * @return array string[]
     */
    public function messages(): array
    {
        return [
            'sei_kana.regex' => 'ミョウジは全角カナで入力してください。',
            'mei_kana.regex' => 'ナマエは全角カナで入力してください。',
            'age.required' => 'ご年齢を入力してください。',
            'tel.regex' => '電話番号は市外局番から-(ハイフン)を含めて入力してください。',
            'term.required' => '利用規約に同意しなければ申込できません。',
        ];
    }

    /**
     * 項目名を定義する
     * @return array string[]
     */
    public function attributes(): array
    {
        return [
        ];
    }
}

