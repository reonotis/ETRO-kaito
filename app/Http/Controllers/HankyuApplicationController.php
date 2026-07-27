<?php

namespace App\Http\Controllers;

use App\Consts\CommonConst;
use App\Http\Requests\HankyuFormRequest;
use App\Mail\Hankyu\NotificationMail;
use App\Mail\Hankyu\ThankYouMail;
use App\Models\Application;
use App\Service\ApplicationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class HankyuApplicationController extends Controller
{

    public function __construct()
    {
    }

    /**
     * 申込期間外か判定する
     * @return bool
     */
    private function checkErrorViewRedirect(): bool
    {
        // 期間外でも表示する設定の場合は期間チェックを行わない
        if (config('app.hankyu_show_outside_period')) {
            return false;
        }

        $now = Carbon::now();
        $from = Carbon::parse('2026-07-16 10:00:00');
        $to = Carbon::parse('2026-07-23 20:00:00');

        if ($from > $now) {
            return true;
        }

        if ($now > $to) {
            return true;
        }
        return false;
    }

    /**
     * 申込画面を表示する
     * @return View
     */
    public function create(): View
    {
        if ($this->checkErrorViewRedirect()) {
            return view('hankyu.outside_period');
        }

        return view('hankyu.application');
    }

    /**
     * 申込処理を行う
     * @param HankyuFormRequest $request
     * @return RedirectResponse
     */
    public function store(HankyuFormRequest $request): RedirectResponse
    {
        // 申込期間外だったら処理させない
        if ($this->checkErrorViewRedirect()) {
            Redirect::route('hankyu_index')->send();
        }

        try {
            $application_service = new ApplicationService();
            DB::beginTransaction();

            $param = [
                'type' => CommonConst::APPLICATION_TYPE_2,
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
            ];
            $application = $application_service->create($param);

            // 申し込み受付通知メール送信
            Mail::to(env('MAIL_FROM_ADDRESS'))
                ->bcc('fujisawareon@yahoo.co.jp')
                ->send(new NotificationMail($application));

            // 申し込み完了メール送信
            Mail::to($application->email)
                ->bcc('fujisawareon@yahoo.co.jp')
                ->send(new ThankYouMail($application));

            DB::commit();
            Redirect::route('hankyu_complete')->send();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
        }
        return redirect()->back()->withInput();
    }

    /**
     * @return View
     */
    public function complete(): View
    {
        return view('hankyu.complete');
    }

    /**
     * @param string $unique_code
     * @return BinaryFileResponse
     */
    public function trackEmailOpen(string $unique_code): BinaryFileResponse
    {
        // アプリケーションを特定
        $application = Application::where('unique_code', $unique_code)->first();

        if ($application) {
            // 閲覧日時を保存
            $application->email_opened_at = now();
            $application->save();
        }

        // 透明ピクセル画像を返す
        return response()->file(public_path('image/transparent_pixel.png'));
    }

    /**
     * @param string $unique_code
     * @return View
     */
    public function viewTicket(string $unique_code): View
    {
        $application_service = new ApplicationService();
        $application = $application_service->getByUniqueCode($unique_code);

        // 無効チェック
        if (is_null($application) || is_null($application->visit_scheduled_date_time)) {
            return view('invalid_request', [
                'message' => '不正なURLです',
            ]);
        }

        if ($application->visit_date_time) {
            return view('invalid_request', [
                'message' => '既にチェックイン済みです',
            ]);
        }

        $from = $application->visit_scheduled_date_time;
        $to = $from->copy()->addMinutes(30);
        $section_name = $from->isoFormat('YYYY年MM月DD日（ddd）') . ' ' . $from->format('H:i') . '〜' . $to->format('H:i');

        return view('hankyu.ticket', [
            'application' => $application,
            'section_name' => $section_name,
        ]);
    }

    /**
     * @param string $unique_code
     * @return View
     */
    public function tearTicket(string $unique_code): View
    {
        $application_service = new ApplicationService();
        $application = $application_service->getByUniqueCode($unique_code);

        // 無効チェック
        if (is_null($application) || is_null($application->visit_scheduled_date_time)) {
            return view('invalid_request', [
                'message' => '不正なURLです',
            ]);
        }

        if ($application->visit_date_time) {
            return view('invalid_request', [
                'message' => '既にチェックイン済みです',
            ]);
        }

        // 来場済みにする
        $application_service->markVisited($application);

        $from = $application->visit_scheduled_date_time;
        $to = $from->copy()->addMinutes(30);
        $section_name = $from->isoFormat('YYYY年MM月DD日（ddd）') . ' ' . $from->format('H:i') . '〜' . $to->format('H:i');

        return view('hankyu.check_in', [
            'application' => $application,
            'section_name' => $section_name,
        ]);
    }
}
