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
     * @param array $param
     * @return Application
     */
    public function create(array $param): Application
    {
        return Application::create($param);
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
        $application->sent_lottery_result_email_flg = 1;
        return $application->save();
    }

    /**
     * 来場履歴を作成し、来場日時を記録する
     * @param Application $application
     * @return bool
     */
    public function markVisited(Application $application): bool
    {
        Visited::create([
            'application_id' => $application->id,
        ]);

        $application->visit_date_time = now();
        return $application->save();
    }

}
