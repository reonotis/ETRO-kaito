<?php

namespace App\Http\Controllers;

use App\Consts\CommonConst;
use App\Http\Requests\StoreAllFormRequest;
use App\Mail\StoresAll\NotificationMail;
use App\Mail\StoresAll\ThankYouMail;
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
use Yajra\DataTables\DataTables;

class StoresAllController extends Controller
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
        $now = Carbon::now();
        $from = Carbon::parse('2026-04-08 00:00:00'); // 08/10～
        $to = Carbon::parse('2026-08-13 23:59:59');

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
            return view('stores_all.outside_period');
        }

        return view('stores_all.application');
    }

    /**
     * 申込処理を行う
     * @param StoreAllFormRequest $request
     * @return RedirectResponse
     */
    public function store(StoreAllFormRequest $request): RedirectResponse
    {
        // 申込期間外だったら処理させない
        if ($this->checkErrorViewRedirect()) {
            Redirect::route('stores_exclude_index')->send();
        }

        try {
            $application_service = new ApplicationService();
            DB::beginTransaction();

            $application = $application_service->create(CommonConst::APPLICATION_TYPE_2, $request->validated());

            // 申し込み受付通知メール送信
            Mail::to(env('MAIL_FROM_ADDRESS'))
                ->bcc('fujisawareon@yahoo.co.jp')
                ->send(new NotificationMail($application));

            // 申し込み完了メール送信
            Mail::to($application->email)
                ->bcc('fujisawareon@yahoo.co.jp')
                ->send(new ThankYouMail($application));

            DB::commit();
            Redirect::route('stores_exclude_complete')->send();
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
        return view('stores_all.complete');
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

        $from = $application->visit_scheduled_date_time;
        $to = $from->copy()->addMinutes(30);
        $section_name = $from->isoFormat('YYYY年MM月DD日（ddd）') . ' ' . $from->format('H:i') . '〜' . $to->format('H:i');

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

        return view('ticket', [
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

        return view('check_in', [
            'application' => $application,
            'section_name' => $section_name,
        ]);
    }

}

