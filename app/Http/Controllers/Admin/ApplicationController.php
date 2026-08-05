<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Service\ApplicationService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\DataTables;

class ApplicationController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getData(Request $request)
    {
        // 申込フォーム種別での絞り込み（必須）
        // 銀座=APPLICATION_TYPE_1, 阪急=APPLICATION_TYPE_2 で完全に別フォーム
        $type = (int)$request->input('type');
        if (!array_key_exists($type, \App\Consts\CommonConst::APPLICATION_TYPE_LIST)) {
            // 不正な type が来た場合は空のレスポンスを返す
            return DataTables::of(Application::query()->whereRaw('1 = 0'))->make(true);
        }

        $applications = Application::select('id',
            'created_at',
            'unique_code',
            'sei',
            'mei',
            'tel',
            'email',
            'sent_lottery_result_email_flg',
            'visit_scheduled_date_time',
            'choice_1',
            \DB::raw("
                CASE
                    WHEN email_opened_at IS NOT NULL THEN '開封済み'
                    WHEN sent_lottery_result_email_flg = 1 THEN '未確認'
                    WHEN visit_scheduled_date_time IS NOT NULL THEN '未送信'
                    ELSE '-'
                END AS mail_status
            "),
            'visit_date_time',
        )->where('type', $type);

        // 管理番号検索
        if ($request->filled('unique_code')) {
            // 全角・半角スペースで分割
            $keywords = preg_split('/[\s　]+/', trim($request->unique_code));
            $applications->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    if ($keyword === '') {
                        continue;
                    }
                    $query->where('unique_code', 'like', "%{$keyword}%");
                }
            });
        }

        // 名前検索（姓・名・連結のいずれにも対応）
        if ($request->filled('name')) {
            $keywords = preg_split('/[\s　]+/', trim($request->name));
            $applications->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    if ($keyword === '') {
                        continue;
                    }
                    $query->where(function ($sub) use ($keyword) {
                        $sub->where('sei', 'like', "%{$keyword}%")
                            ->orWhere('mei', 'like', "%{$keyword}%")
                            ->orWhereRaw("CONCAT(sei, ' ', mei) LIKE ?", ["%{$keyword}%"])
                            ->orWhereRaw("CONCAT(sei, mei) LIKE ?", ["%{$keyword}%"]);
                    });
                }
            });
        }

        // メール検索
        if ($request->filled('email')) {
            // 全角・半角スペースで分割
            $keywords = preg_split('/[\s　]+/', trim($request->email));

            $applications->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    if ($keyword === '') {
                        continue;
                    }
                    $query->where('email', 'like', "%{$keyword}%");
                }
            });
        }

        // メールステータス検索
        if ($request->filled('mail_status')) {
            if ($request->mail_status === '未確認') {
                $applications->whereNull('email_opened_at');
            } elseif ($request->mail_status === '閲覧済み') {
                $applications->whereNotNull('email_opened_at');
            }
        }

        // 来場予定日時の期間検索（visit_scheduled_date_time）
        if ($request->filled('visit_scheduled_from')) {
            $applications->whereDate('visit_scheduled_date_time', '>=', $request->input('visit_scheduled_from'));
        }
        if ($request->filled('visit_scheduled_to')) {
            $applications->whereDate('visit_scheduled_date_time', '<=', $request->input('visit_scheduled_to'));
        }

        // 来場日時の期間検索（visit_date_time）
        if ($request->filled('visit_from')) {
            $applications->whereDate('visit_date_time', '>=', $request->input('visit_from'));
        }
        if ($request->filled('visit_to')) {
            $applications->whereDate('visit_date_time', '<=', $request->input('visit_to'));
        }

        return DataTables::of($applications)
            ->editColumn('created_at', function ($application) {
                return Carbon::parse($application->created_at)->format('Y/m/d H:i:s'); // 秒あり
            })
            ->editColumn('name', function ($application) {
                return $application->sei . ' ' . $application->mei;
            })
            // `name` は実テーブルの列ではないため、姓・名で明示的に並び替える
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('sei', $order)->orderBy('mei', $order);
            })
            ->editColumn('choice_1', function ($application) {
                return $application->choice_1 !== null && $application->choice_1 !== ''
                    ? $application->choice_1
                    : '-';
            })
            ->editColumn('visit_scheduled_date_time', function ($application) {
                return $application->visit_scheduled_date_time
                    ? Carbon::parse($application->visit_scheduled_date_time)->format('Y/m/d H:i')
                    : '-';
            })
            ->editColumn('visit_date_time', function ($application) {
                return $application->visit_date_time
                    ? Carbon::parse($application->visit_date_time)->format('Y/m/d H:i')
                    : '-';
            })
            ->rawColumns(['visit_date_time'])
            ->make(true);
    }

    /**
     * 申込者一覧画面を表示
     * @return View
     */
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    /**
     * @return View
     */
    public function list(int $type): View
    {
        // 不正な type は 404
        if (!array_key_exists($type, \App\Consts\CommonConst::APPLICATION_TYPE_LIST)) {
            abort(404);
        }

        $typeLabel = \App\Consts\CommonConst::APPLICATION_TYPE_LIST[$type];

        if ($type === \App\Consts\CommonConst::APPLICATION_TYPE_1) {
            return view('admin.list-ginza', compact('type', 'typeLabel'));
        }

        return view('admin.list-hankyu', compact('type', 'typeLabel'));
    }

    /**
     * @return
     */
    public function downloadCsv(Request $request)
    {
        // 申込フォーム種別での絞り込み（必須）
        $type = (int)$request->input('type');
        if (!array_key_exists($type, \App\Consts\CommonConst::APPLICATION_TYPE_LIST)) {
            abort(404);
        }

        $applications = Application::select('id',
            'created_at',
            'unique_code',
            'sei',
            'mei',
            'tel',
            'email',
            'zip21',
            'zip22',
            'pref21',
            'address21',
            'street21',
            'choice_1',
            'visit_scheduled_date_time',
            'visit_date_time',
            \DB::raw("
            CASE
                WHEN email_opened_at IS NOT NULL THEN '開封済み'
                WHEN sent_lottery_result_email_flg = 1 THEN '未確認'
                WHEN visit_scheduled_date_time IS NOT NULL THEN '未送信'
                ELSE '-'
            END AS mail_status
        ")
        )
            ->where('type', $type)
            ->get();

        $typeLabel = \App\Consts\CommonConst::APPLICATION_TYPE_LIST[$type];

        // 「来場予定日時を更新」アップロードの列位置（B列=管理番号, H列=セクション, I列=来場予定日時）と一致させる
        $csvHeader = [
            '申込日時', '管理番号', '名前', '電話番号', 'メールアドレス', '住所', 'メールステータス', 'セクション', '来場予定日時', '来場日時'
        ];

        $response = new StreamedResponse(function () use ($applications, $csvHeader) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, $csvHeader);

            foreach ($applications as $application) {

                // 郵便番号 + 住所を整形
                $zipcode = trim("{$application->zip21}-{$application->zip22}");
                $zipcode = ($zipcode !== '-') ? "{$zipcode} " : '';
                $address = trim("{$application->pref21} {$application->address21} {$application->street21}");
                $fullAddress = trim($zipcode . $address) ?: '-';

                fputcsv($file, [
                    $application->created_at,
                    $application->unique_code,
                    $application->sei . ' ' . $application->mei,
                    $application->tel,
                    $application->email,
                    $fullAddress,
                    $application->mail_status,
                    $application->choice_1 !== null && $application->choice_1 !== '' ? $application->choice_1 : '',
                    $application->visit_scheduled_date_time
                        ? Carbon::parse($application->visit_scheduled_date_time)->format('Y/m/d H:i')
                        : '-',
                    $application->visit_date_time
                        ? Carbon::parse($application->visit_date_time)->format('Y/m/d H:i')
                        : '-',
                ]);
            }

            fclose($file);
        });

        $filename = '申込一覧_' . $typeLabel . '.csv';
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . rawurlencode($filename) . '"; filename*=UTF-8\'\'' . rawurlencode($filename));
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }

    /**
     * CSVをアップロードし、choice_1(セクション列/H列)と来場予定日時(I列)だけを一括更新する
     * CSVダウンロードで出力される列順（申込日時,管理番号,名前,電話番号,メールアドレス,住所,メールステータス,セクション,来場予定日時,来場日時）と一致させること
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateVisitSchedule(Request $request)
    {
        $request->validate([
            'type' => ['required', 'integer'],
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $type = (int)$request->input('type');
        if (!array_key_exists($type, \App\Consts\CommonConst::APPLICATION_TYPE_LIST)) {
            abort(404);
        }

        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');

        $updated = 0;
        $errors = [];
        $rowNumber = 0;
        $isHeaderRow = true;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if ($isHeaderRow) {
                $isHeaderRow = false;
                continue;
            }

            // 空行はスキップ
            if (count(array_filter($row, fn ($v) => $v !== null && $v !== '')) === 0) {
                continue;
            }

            // BOM除去（先頭列にBOMが残る場合がある）
            $uniqueCode = trim(preg_replace('/^\xEF\xBB\xBF/', '', $row[1] ?? ''));
            $choice1 = trim($row[7] ?? '');
            $visitScheduled = trim($row[8] ?? '');

            if ($uniqueCode === '') {
                $errors[] = "{$rowNumber}行目: 管理番号が空です";
                continue;
            }

            $application = Application::where('unique_code', $uniqueCode)->where('type', $type)->first();
            if (!$application) {
                $errors[] = "{$rowNumber}行目: 管理番号「{$uniqueCode}」が見つかりません";
                continue;
            }

            $application->choice_1 = $choice1 !== '' ? $choice1 : null;

            if ($visitScheduled === '' || $visitScheduled === '-') {
                $application->visit_scheduled_date_time = null;
            } else {
                try {
                    $application->visit_scheduled_date_time = Carbon::parse($visitScheduled);
                } catch (\Exception $e) {
                    $errors[] = "{$rowNumber}行目: 来場予定日時「{$visitScheduled}」を解釈できません（管理番号: {$uniqueCode}）";
                    continue;
                }
            }

            $application->save();
            $updated++;
        }

        fclose($handle);

        return response()->json([
            'updated' => $updated,
            'errors' => $errors,
        ]);
    }

    /**
     * 「未送信」の申込者に対して、当選通知メールを一斉送信する
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendWinnerMail(Request $request)
    {
        $request->validate([
            'type' => ['required', 'integer'],
        ]);

        $type = (int)$request->input('type');
        if (!array_key_exists($type, \App\Consts\CommonConst::APPLICATION_TYPE_LIST)) {
            abort(404);
        }

        // 「未送信」＝来場予定日時が確定していて、まだ当選メールを送っていない申込者
        $applications = Application::where('type', $type)
            ->whereNotNull('visit_scheduled_date_time')
            ->where('sent_lottery_result_email_flg', 0)
            ->whereNull('email_opened_at')
            ->get();

        $application_service = new ApplicationService();
        $sent = 0;
        $errors = [];

        foreach ($applications as $application) {
            try {
                $from = Carbon::parse($application->visit_scheduled_date_time);
                $to = $from->copy()->addMinutes(30);
                $sectionName = $from->isoFormat('YYYY年MM月DD日（ddd）') . ' ' . $from->format('H:i') . '〜' . $to->format('H:i');

                if ($type === \App\Consts\CommonConst::APPLICATION_TYPE_1) {
                    Mail::to($application->email)->send(new \App\Mail\Ginza\WinnerNotificationMail($application, $sectionName));
                } else {
                    Mail::to($application->email)->send(new \App\Mail\Hankyu\WinnerNotificationMail($application, $sectionName));
                }

                $application_service->markSendMail($application);
                $sent++;
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                $errors[] = "管理番号「{$application->unique_code}」への送信に失敗しました";
            }
        }

        return response()->json([
            'sent' => $sent,
            'errors' => $errors,
        ]);
    }

    /**
     * 阪急：実行日の翌日以降に来場予定の申込者に対して、来場リマインドメールを一斉送信する
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReminderMail(Request $request)
    {
        $request->validate([
            'type' => ['required', 'integer'],
        ]);

        $type = (int)$request->input('type');
        if ($type !== \App\Consts\CommonConst::APPLICATION_TYPE_2) {
            abort(404);
        }

        // 「実行日の翌日以降に来場予定」＝来場予定日時が確定していて、その日付が明日以降の申込者
        $applications = Application::where('type', $type)
            ->whereNotNull('visit_scheduled_date_time')
            ->whereDate('visit_scheduled_date_time', '>=', Carbon::tomorrow())
            ->get();

        $sent = 0;
        $errors = [];

        foreach ($applications as $application) {
            try {
                $from = Carbon::parse($application->visit_scheduled_date_time);
                $to = $from->copy()->addMinutes(30);
                $sectionName = $from->isoFormat('YYYY年MM月DD日（ddd）') . ' ' . $from->format('H:i') . '〜' . $to->format('H:i');

                Mail::to($application->email)->send(new \App\Mail\Hankyu\ReminderMail($application, $sectionName));
                $sent++;
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                $errors[] = "管理番号「{$application->unique_code}」への送信に失敗しました";
            }
        }

        return response()->json([
            'sent' => $sent,
            'errors' => $errors,
        ]);
    }

}
