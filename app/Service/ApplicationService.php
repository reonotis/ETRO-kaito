<?php

namespace App\Service;

use App\Models\Application;
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
     * @return Application
     */
    public function create(array $request): Application
    {
        return Application::create($request);
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
     * 来場処理を行う
     * @param Application $application
     * @return bool
     */
    public function markVisited(Application $application): bool
    {
        return $application->update([
            'visit_date_time' => Carbon::now(),
        ]);
    }

}
