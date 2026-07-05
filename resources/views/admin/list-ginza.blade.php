<x-app-layout>
    {{-- 旧ヘッダーは新デザインに合わせて非表示。タイトルは本文側で表示する --}}

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

    <!-- daterangepicker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

    <!-- daterangepicker JS（moment.js が依存） -->
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    @vite([
        'resources/css/admin.css',
        'resources/js/admin/ginza-list.js',
    ])
    <script>
        window.Laravel = {
            route_download_csv: "{{ route('applications.download-csv') }}",
            route_applications_data: "{{ route('applications.data') }}",
            route_update_visit_schedule: "{{ route('applications.update-visit-schedule') }}",
            route_send_winner_mail: "{{ route('applications.send-winner-mail') }}",
        };

        window.csrfToken = "{{ csrf_token() }}";
    </script>

    <div class="py-8 px-4 mx-auto space-y-6">

        {{-- ===== タイトル + CSV ダウンロード ===== --}}
        <div class="bg-white rounded-xl shadow-sm px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                    {{-- people icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-3.13a4 4 0 10-6 0m6 0a4 4 0 11-6 0m9-2a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-800 leading-tight">申込・来場管理（{{ $typeLabel }}）</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $typeLabel }}フォームの申込者一覧</p>
                </div>
            </div>

            <div class="flex flex-col items-stretch gap-2">
                <button id="csv_download" type="button" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                    </svg>
                    CSVダウンロード
                </button>
                <button id="visit_schedule_upload_btn" type="button" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V3m0 0L8 7m4-4l4 4" />
                    </svg>
                    来場予定日時を更新
                </button>
                <input type="file" id="visit_schedule_file_input" accept=".csv,text/csv" class="hidden">
            </div>
        </div>

        {{-- ===== 絞り込み ===== --}}
        <div class="bg-white rounded-xl shadow-sm" x-data="{ open: true }">
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2 text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M6 12h12M10 18h4" />
                    </svg>
                    <span class="font-semibold">絞り込み</span>
                </div>
                <button type="button" @click="open = !open"
                    class="text-amber-700 hover:text-amber-800 text-sm font-medium flex items-center gap-1">
                    <span x-text="open ? '閉じる' : '開く'"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform" :class="{ 'rotate-180': !open }"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
            </div>

            <div x-show="open" x-transition.duration.150ms class="px-6 pb-5">
                <div class="flex gap-4 flex-wrap">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">管理番号</label>
                        <input type="text" id="search_unique_code"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="例) 48IKWjVOEf">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">名前</label>
                        <input type="text" id="search_name"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="例) テスト テスト">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">メールアドレス</label>
                        <input type="text" id="search_email"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="例) test@test.jp">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">メールステータス</label>
                        <select id="search_mail_status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">すべて</option>
                            <option value="未確認">未確認</option>
                            <option value="閲覧済み">閲覧済み</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs text-gray-600 mb-1">来場予定日時</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input type="text" id="search_visit_scheduled_range" autocomplete="off" readonly
                                   class="w-full pl-9 pr-9 py-2 border border-gray-300 rounded-md text-sm bg-white cursor-pointer focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="期間を選択（YYYY/MM/DD 〜 YYYY/MM/DD）">
                            <button type="button" id="clear_visit_scheduled_range"
                                    class="hidden absolute inset-y-0 right-2 my-auto h-6 w-6 items-center justify-center text-gray-400 hover:text-gray-600 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <input type="hidden" id="search_visit_scheduled_from">
                            <input type="hidden" id="search_visit_scheduled_to">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs text-gray-600 mb-1">来場日時</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input type="text" id="search_visit_range" autocomplete="off" readonly
                                   class="w-full pl-9 pr-9 py-2 border border-gray-300 rounded-md text-sm bg-white cursor-pointer focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="期間を選択（YYYY/MM/DD 〜 YYYY/MM/DD）">
                            <button type="button" id="clear_visit_range"
                                    class="hidden absolute inset-y-0 right-2 my-auto h-6 w-6 items-center justify-center text-gray-400 hover:text-gray-600 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <input type="hidden" id="search_visit_from">
                            <input type="hidden" id="search_visit_to">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-5">
                    <button id="resetBtn" type="button"
                        class="px-5 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold border border-gray-200 transition">
                        リセット
                    </button>
                    <button id="searchBtn" type="button"
                        class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition">
                        検索
                    </button>
                </div>
            </div>

            {{-- 絞り込み条件チップ --}}
            <div id="filter_chips_wrapper" class="hidden border-t border-gray-100 px-6 py-3 bg-amber-50/50 rounded-b-xl">
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <div class="flex items-center gap-2 flex-wrap">
                        <div class="flex items-center gap-1 text-amber-700 text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 018 17v-3.586L3.293 6.707A1 1 0 013 6V4z"/>
                            </svg>
                            絞り込み条件
                        </div>
                        <div id="filter_chips" class="flex items-center gap-2 flex-wrap"></div>
                    </div>
                    <button id="clear_filters" type="button" class="text-sm text-amber-700 hover:text-amber-900 font-medium">すべてクリア</button>
                </div>
            </div>
        </div>

        {{-- ===== 検索結果 ===== --}}
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                    </svg>
                    <span class="font-semibold text-gray-800">検索結果</span>
                    <span id="result_count_badge" class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold">0件</span>
                    <button id="refresh_btn" type="button"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582M20 20v-5h-.581M5.5 9A7.5 7.5 0 0118.418 7.582M18.5 15A7.5 7.5 0 015.582 16.418"/>
                        </svg>
                        最新の情報に更新
                    </button>
                </div>
                <button id="send_winner_mail_btn" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    未送信者へ一斉送信
                </button>
            </div>

            <div class="px-6 pt-4 pb-2">
                <table id="application_table" class="min-w-full bg-white">
                    <thead>
                    <tr>
                        <th>申込日時</th>
                        <th>管理番号</th>
                        <th>名前</th>
                        <th>電話番号</th>
                        <th>メールアドレス</th>
                        <th>メールステータス</th>
                        <th>来場予定日時</th>
                        <th>来場日時</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
