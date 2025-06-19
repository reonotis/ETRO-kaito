<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ApplicationMail;
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
use Illuminate\Http\Request;
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
            'choice_4',
            'visit_scheduled_date_time',
            'sent_lottery_result_email_flg',
            'visit_date_time',
            'email_opened_at',
            \DB::raw("
            CASE
                WHEN email_opened_at IS NOT NULL THEN '閲覧済み'
                WHEN visit_scheduled_date_time IS NOT NULL AND sent_lottery_result_email_flg = 1 THEN '送信済'
                WHEN visit_scheduled_date_time IS NOT NULL THEN '招待メール未送信'
                ELSE '-'
            END AS status
        ")
        );

        // 名前検索
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

        // ヨミ検索
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

        // 来場予定日時検索
        if ($request->has('visit_scheduled_date_time') && $request->visit_scheduled_date_time) {
            $date = trim($request->visit_scheduled_date_time);

            // MM/DD HH:ii 形式を分解して日付と時間を取得
            [$month_day, $time] = explode(' ', $date);
            [$month, $day] = explode('/', $month_day);
            [$hour, $minute] = explode(':', $time);

            // 現在の年を取得
            $currentYear = now()->year;

            // 開始時間 (例: 2025-05-17 12:15:00)
            $startDateTime = Carbon::create($currentYear, $month, $day, $hour, $minute, 0);
            // 終了時間 (例: 2025-05-17 12:15:59)
            $endDateTime = $startDateTime->copy()->addSeconds(59);

            // 指定された1分間の範囲で検索
            $applications->whereBetween('visit_scheduled_date_time', [$startDateTime, $endDateTime]);
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
                $address = trim("{$application->pref21} {$application->address21}<br>{$application->street21}");

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

    /**
     * @param Request $request
     */
    public function dashboard()
    {
        $application_count = Application::whereNotNull('visit_scheduled_date_time')->count();

        return view('dashboard', [
            'count' => $application_count
        ]);
    }

    /**
     * @return void
     */
    public function downloadCsv()
    {
        $applications = Application::select(
            'created_at',
            'unique_code',
            \DB::raw("CONCAT(sei, ' ', mei, '（', sei_kana, mei_kana, '）') AS full_name"),
            'sex',
            'age',
            'tel',
            'email',
            \DB::raw("CONCAT(zip21, '-', zip22, ' ', pref21, ' ', address21, ' ', street21) AS full_address"),
            'choice_4',
            'visit_scheduled_date_time',
            'visit_date_time',
            'sent_lottery_result_email_flg',
            'email_opened_at'
        )->get();

        $csvHeader = [
            '申込日時', '管理番号', '名前', '性別', '年齢', '電話番号', 'メール', '住所', '来場予定日時', 'ステータス', '来場時刻', 'グループ名（呼び出し番号）'
        ];

        $response = new StreamedResponse(function () use ($applications, $csvHeader) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, $csvHeader);

            foreach ($applications as $application) {

                // ステータスを判定
                $status = '-';
                if (!empty($application->email_opened_at)) {
                    $status = '閲覧済み';
                } elseif (!empty($application->visit_scheduled_date_time)) {
                    $status = $application->sent_lottery_result_email_flg ? '招待メール送信済' : '招待メール未送信';
                }

                fputcsv($file, [
                    $application->created_at,
                    $application->unique_code,
                    $application->full_name,
                    Common::SEX_LIST[$application->sex] ?? '',
                    $application->age,
                    $application->tel,
                    $application->email,
                    $application->full_address,
                    $application->visit_scheduled_date_time,
                    $status,
                    $application->visit_date_time,
                    $application->choice_4,
                ]);
            }

            fclose($file);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="申込一覧.csv"');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }

    /**
     * @return JsonResponse
     */
    public function sendWinnerMail(): JsonResponse
    {
        $applications = Application::whereNotNull('visit_scheduled_date_time')
            ->where('sent_lottery_result_email_flg', 0)->get();

        if ($applications->count() == 0) {
            return response()->json(['message' => '送信する対象者がありません。もしくは全ての対象者に送信済みです']);
        }

        $application_service = new ApplicationService();
        foreach ($applications as $application) {
            Mail::to($application->email)->send(new ApplicationMail($application));

            // 来場済みにする
            $application_service->markSendMail($application);
        }

        return response()->json(['message' => $applications->count() . '件のメールが送信されました。']);
    }

}
