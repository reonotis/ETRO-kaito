<?php

namespace App\Http\Controllers;

use App\Consts\CommonConst;
use App\Http\Requests\HankyuFormRequest;
use App\Mail\Hankyu\NotificationMail;
use App\Mail\Hankyu\ThankYouMail;
use App\Service\ApplicationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

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
        $now = Carbon::now();
        $from = Carbon::parse('2026-07-08 00:00:00');
        $to = Carbon::parse('2026-07-23 23:59:59');

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
}
