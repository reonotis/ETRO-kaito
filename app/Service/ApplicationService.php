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
     * @return array
     */
    public function create(array $request): array
    {
        $application = Application::create([
            'name' => $request['name'],
            'address' => $request['address'],
            'tel' => $request['tel'],
            'email' => $request['email'],
        ]);

        $target_event_list = [];
        foreach ($request['target_date'] as $target_date) { // 希望イベントを複数選択できるようにする
            $target_event_list[] = TargetEvent::create([
                'application_id' => $application->id,
                'target_number' => $target_date,
            ]);
        }

        return [
            'application' => $application,
            'target_events' => $target_event_list,
        ];
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
