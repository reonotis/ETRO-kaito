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
use Illuminate\Contracts\View\Factory;
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
            'created_at',
            'unique_code',
            'name',
            'tel',
            'email',
            'address',
            'sent_lottery_result_email_flg',
            'visit_scheduled_date_time',
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
        );

        // 管理番号検索
        if ($request->has('unique_code') && $request->unique_code) {
            // 全角・半角スペースで分割
            $keywords = preg_split('/[\s　]+/', trim($request->unique_code));
            $applications->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->where(function ($subQuery) use ($keyword) {
                        $subQuery->where('unique_code', 'like', "%{$keyword}%");
                    });
                }
            });
        }

        // 名前検索
        if ($request->has('name') && $request->name) {
            $applications->where('name', 'like', "%{$request->name}%");
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
            ->editColumn('created_at', function ($application) {
                return Carbon::parse($application->created_at)->format('Y/m/d H:i:s'); // 秒あり
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
        $application_count = Application::whereNotNull('visit_scheduled_date_time')->count();

        return view('dashboard', [
            'count' => $application_count
        ]);
    }

    /**
     * @return
     */
    public function downloadCsv()
    {

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
        )->get();

        $csvHeader = [
            '申込日時', '管理番号', '名前', '電話番号', 'メールアドレス', '住所', '10/4(展示会)',  '10/4(レセプション)',  '10/5', 'メールステータス'
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
                    $application->name,
                    $application->tel,
                    $application->email,
                    $application->address,
                    $application->date_1,
                    $application->date_2,
                    $application->date_3,
                    $application->mail_status,
                    $application->visit_date_time,
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

}
