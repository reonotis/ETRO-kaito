<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
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
        // 申込フォーム種別での絞り込み（必須）
        // 銀座=APPLICATION_TYPE_1, 全国=APPLICATION_TYPE_2 で完全に別フォーム
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
            \DB::raw("
            CASE
                WHEN email_opened_at IS  NULL  THEN '未確認'
                WHEN email_opened_at IS NOT NULL THEN '閲覧済み'
                ELSE '-'
            END AS mail_status
            "),
                \DB::raw("
                (SELECT GROUP_CONCAT(DATE_FORMAT(visited.created_at, '%Y/%m/%d %H:%i:%s') ORDER BY visited.created_at ASC SEPARATOR '<br>')
                    FROM visited
                    WHERE visited.application_id = application.id AND visited.deleted_at IS NULL) AS visit_dates
            ")
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

        // 来場日時検索（visited テーブルとの紐付き）
        if ($request->filled('visit_from') || $request->filled('visit_to')) {
            $applications->whereExists(function ($query) use ($request) {
                $query->select(\DB::raw(1))
                    ->from('visited')
                    ->whereColumn('visited.application_id', 'application.id')
                    ->whereNull('visited.deleted_at');

                if ($request->filled('visit_from')) {
                    $query->whereDate('visited.created_at', '>=', $request->visit_from);
                }
                if ($request->filled('visit_to')) {
                    $query->whereDate('visited.created_at', '<=', $request->visit_to);
                }
            });
        }

        return DataTables::of($applications)
            ->editColumn('created_at', function ($application) {
                return Carbon::parse($application->created_at)->format('Y/m/d H:i:s'); // 秒あり
            })
            ->editColumn('name', function ($application) {
                return $application->sei . ' ' . $application->mei;
            })
            ->editColumn('visit_scheduled_date_time', function ($application) {
                return $application->visit_scheduled_date_time
                    ? Carbon::parse($application->visit_scheduled_date_time)->format('m/d H:i')
                    : '-';
            })
            ->editColumn('visit_dates', function ($application) {
                return $application->visit_dates ?: '-';
            })
            ->rawColumns(['visit_dates'])
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

        return view('admin.list-all', compact('type', 'typeLabel'));
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
            'name',
            'tel',
            'email',
            'address',
            \DB::raw("
            CASE
                WHEN EXISTS(SELECT 1 FROM target_event WHERE application_id = application.id AND target_number = 1 AND deleted_at IS NULL) THEN '希望'
                ELSE '-'
            END AS date_1
        "),
            \DB::raw("
            CASE
                WHEN EXISTS(SELECT 1 FROM target_event WHERE application_id = application.id AND target_number = 2 AND deleted_at IS NULL) THEN '希望'
                ELSE '-'
            END AS date_2
        "),
            \DB::raw("
            CASE
                WHEN EXISTS(SELECT 1 FROM target_event WHERE application_id = application.id AND target_number = 3 AND deleted_at IS NULL) THEN '希望'
                ELSE '-'
            END AS date_3
        "),
            \DB::raw("
            CASE
                WHEN email_opened_at IS  NULL  THEN '未確認'
                WHEN email_opened_at IS NOT NULL THEN '閲覧済み'
                ELSE '-'
            END AS mail_status
        "),
            \DB::raw("
            (SELECT GROUP_CONCAT(DATE_FORMAT(visited.created_at, '%Y/%m/%d %H:%i:%s') ORDER BY visited.created_at ASC SEPARATOR '<br>')
                FROM visited
                WHERE visited.application_id = application.id AND visited.deleted_at IS NULL) AS visit_dates
        ")
        )
            ->where('type', $type)
            ->get();

        $typeLabel = \App\Consts\CommonConst::APPLICATION_TYPE_LIST[$type];

        $csvHeader = [
            '申込日時', '管理番号', '名前', '電話番号', 'メールアドレス', '住所', '10/4(展示会)',  '10/4(レセプション)',  '10/5', 'メールステータス', '来場日時'
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

                // 来場日時の処理
                $visitDates = '-';
                if ($application->visit_dates) {
                    // HTMLの<br>タグをカンマに置き換えて、CSV用に整形
                    $visitDates = str_replace('<br>', '", "', $application->visit_dates);
                    // 最初と最後にクォーテーションを追加
                    $visitDates = '"' . $visitDates . '"';
                }

                fputcsv($file, [
                    $application->created_at,
                    $application->unique_code,
                    $application->name,
                    $application->tel,
                    $application->email,
                    $application->address,
                    $application->date_1,
                    $application->date_2,
                    $application->date_3,
                    $application->mail_status,
                    $visitDates,
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

}
