<?php

namespace App\Http\Controllers;

use App\Mail\ThankYouMail;
use App\Mail\NotificationMail;
use App\Consts\Common;
use App\Http\Requests\ApplicationFormRequest;
use App\Service\ApplicationService;
use App\Models\Application;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\DataTables;


class ApplicationController extends Controller
{

    public function __construct()
    {
        if ($this->checkErrorViewRedirect()) {
            Redirect::route('application_period')->send();
        }
    }

    /**
     * 申込期間画面に行くか判定する
     * @return bool
     */
    private function checkErrorViewRedirect(): bool
    {
        // 既にエラー画面に行こうとしている場合は再リダイレクトさせない
        if (\Route::currentRouteName() ==  'application_period') {
            return false;
        }

        $now = Carbon::now();
        $from = Carbon::parse('2025-06-10 12:00:00'); // 2025-06-20
        $to = Carbon::parse('2025-06-23 23:59:59');

        if ($from > $now) {
            return true;
        }

        if ($now > $to) {
            return true;
        }
        return false;
    }

    /**
     * @return View
     */
    public function outsidePeriod(): View
    {
        return view('outside_period');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('application');
    }

    /**
     * @param ApplicationFormRequest $request
     * @return RedirectResponse
     */
    public function store(ApplicationFormRequest $request): RedirectResponse
    {
        try {
            $application_service = new ApplicationService();
            DB::beginTransaction();

            $application = $application_service->create($request->all());

            // 申し込み完了メール送信
            Mail::to($application->email)
                ->bcc('fujisawareon@yahoo.co.jp')
                ->send(new ThankYouMail($application));

            // 申し込み受付通知メール送信
            Mail::to(env('MAIL_FROM_ADDRESS'))
                ->bcc('fujisawareon@yahoo.co.jp')
                ->send(new NotificationMail($application));

            DB::commit();
            Redirect::route('application_complete')->send();
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
        return view('complete');
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

    /**
     * @param Request $request
     * @return mixed
     */
    public function getData(Request $request)
    {

        $applications = Application::select('id',
            \DB::raw("CONCAT(sei, ' ', mei) AS name"),
            \DB::raw("CONCAT(sei_kana, ' ', mei_kana) AS yomi"),
            'created_at',
            'unique_code',
            'email',
            'sex',
            'age',
            'tel',
            'zip21',
            'zip22',
            'pref21',
            'address21',
            'street21',
            'visit_scheduled_date_time',
            'sent_lottery_result_email_flg',
            'visit_date_time',
        );

        // 検索処理
        if ($request->has('name') && $request->name) {
            // 全角・半角スペースで分割
            $keywords = preg_split('/[\s　]+/', trim($request->name));

            $applications->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->where(function ($subQuery) use ($keyword) {
                        $subQuery->where('sei', 'like', "%{$keyword}%")
                            ->orWhere('mei', 'like', "%{$keyword}%");
                    });
                }
            });
        }

        if ($request->has('yomi') && $request->yomi) {
            // トリムし、BOMを削除してからUTF-8に変換
            $input = trim($request->yomi);
            $input = preg_replace('/^\xEF\xBB\xBF/', '', $input); // BOMを削除
            $input = mb_convert_encoding($input, 'UTF-8', 'auto');

            // 全角・半角スペースで分割 (マルチバイト対応)
            $keywords = mb_split('\s+', $input);
            $keywords = array_filter($keywords); // 空要素を除去

            $applications->where(function ($query) use ($keywords) {

                foreach ($keywords as $keyword) {
                    $query->where(function ($subQuery) use ($keyword) {
                        $subQuery->where('sei_kana', 'like', "%{$keyword}%")
                            ->orWhere('mei_kana', 'like', "%{$keyword}%");
                    });
                }
            });
        }

        // メール検索
        if ($request->has('email') && $request->email) {
            // 全角・半角スペースで分割
            $keywords = preg_split('/[\s　]+/', trim($request->email));

            $applications->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->where('email', 'like', "%{$keyword}%");
                }
            });
        }


        return DataTables::of($applications)
            ->editColumn('age', function ($application) {
                return $application->age ? $application->age . '歳' : '-';
            })
            ->editColumn('sex', function ($application) {
                return Common::SEX_LIST[$application->sex] ?? '不明';
            })
            ->editColumn('created_at', function ($application) {
                return Carbon::parse($application->created_at)->format('Y/m/d H:i:s'); // 秒あり
            })
            ->addColumn('full_address', function ($application) {
                // 郵便番号を整形
                $zipcode = trim("{$application->zip21}-{$application->zip22}");
                $zipcode = ($zipcode !== '-') ? "{$zipcode}<br>" : '';

                // 住所を整形
                $address = trim("{$application->pref21} {$application->address21} {$application->street21}");

                // 郵便番号 + 住所を組み合わせ
                if ($zipcode && $address) {
                    return "{$zipcode}{$address}";
                } elseif ($zipcode) {
                    return $zipcode;
                } elseif ($address) {
                    return $address;
                } else {
                    return '-';
                }
            })
            ->editColumn('visit_scheduled_date_time', function ($application) {
                return $application->visit_scheduled_date_time
                    ? Carbon::parse($application->visit_scheduled_date_time)->format('m/d H:i')
                    : '-';
            })
            ->editColumn('visit_date_time', function ($application) {
                return $application->visit_date_time
                    ? Carbon::parse($application->visit_date_time)->format('m/d H:i')
                    : '-';
            })
            ->rawColumns(['full_address']) // HTMLをそのまま表示
            ->make(true);
    }
}

