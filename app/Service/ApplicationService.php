<?php

namespace App\Service;

use App\Models\Application;
use App\Models\TargetEvent;
use App\Models\Visited;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ApplicationService
{
    function __construct()
    {
    }

    /**
     * 登録処理を行う
     * @param array $request
     */
    public function create(int $type, array $request)
    {
        return Application::create([
            'type' => $type,
            'sei' => $request['sei'],
            'mei' => $request['mei'],
            'sei_kana' => $request['sei_kana'],
            'mei_kana' => $request['mei_kana'],
            'sex' => $request['sex'],
            'tel' => $request['tel'],
            'age' => $request['age'],
            'email' => $request['email'],
            'zip21' => $request['zip21'],
            'zip22' => $request['zip22'],
            'pref21' => $request['pref21'],
            'address21' => $request['address21'],
            'street21' => $request['street21'],
            'choice_1' => $request['choice_1'],
        ]);
    }

    /**
     * ユニークコードから申込データを取得する
     * @param string $unique_code
     * @return Application|null
     */
    public function getByUniqueCode(string $unique_code): ?Application
    {
        return Application::where('unique_code', $unique_code)->first();
    }

    /**
     * 当選メール送信済みにする
     * @param Application $application
     * @return bool
     */
    public function markSendMail(Application $application): bool
    {
        return $application->update([
            'sent_lottery_result_email_flg' => 1,
        ]);
    }

    /**
     * 来場履歴を作成する
     * @param int $application_id
     */
    public function markVisited(int $application_id)
    {
        return Visited::create([
            'application_id' => $application_id,
        ]);
    }

}
