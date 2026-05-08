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

    @vite(['resources/css/admin.css'])

    <div class="py-8 px-4 mx-auto space-y-6 max-w-7xl">

        {{-- ===== タイトル ===== --}}
        <div class="bg-white rounded-xl shadow-sm px-6 py-5 flex items-center gap-3">
            <div class="w-11 h-11 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                {{-- clipboard icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">申込フォーム一覧</h1>
                <p class="text-sm text-gray-500 mt-0.5">確認したい申込フォームを選択してください</p>
            </div>
        </div>

        {{-- ===== フォーム選択カード ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- 銀座カード --}}
            <a href="{{ route('admin_list', ['type' => \App\Consts\CommonConst::APPLICATION_TYPE_1]) }}"
               class="dashboard-card group relative block bg-white rounded-xl shadow-sm hover:shadow-lg overflow-hidden transition-all duration-300">
                <span class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-amber-400 to-amber-700"></span>
                <div class="px-6 py-6 flex items-center gap-5">
                    <div class="flex-shrink-0 w-14 h-14 rounded-full bg-amber-50 text-amber-700 flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                        {{-- building icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-amber-700 tracking-wider uppercase">Ginza</p>
                        <h2 class="mt-0.5 text-lg font-bold text-gray-800">銀座のリストを確認する</h2>
                        <p class="mt-1 text-sm text-gray-500">銀座エリアの申込者一覧へ</p>
                    </div>
                    <div class="flex-shrink-0 text-amber-600 group-hover:translate-x-1 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>

            {{-- 全国カード --}}
            <a href="{{ route('admin_list', ['type' => \App\Consts\CommonConst::APPLICATION_TYPE_2]) }}"
               class="dashboard-card group relative block bg-white rounded-xl shadow-sm hover:shadow-lg overflow-hidden transition-all duration-300">
                <span class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-amber-400 to-amber-700"></span>
                <div class="px-6 py-6 flex items-center gap-5">
                    <div class="flex-shrink-0 w-14 h-14 rounded-full bg-amber-50 text-amber-700 flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                        {{-- building icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-amber-700 tracking-wider uppercase">ALL</p>
                        <h2 class="mt-0.5 text-lg font-bold text-gray-800">全国のリストを確認する</h2>
                        <p class="mt-1 text-sm text-gray-500">全国エリアの申込者一覧へ</p>
                    </div>
                    <div class="flex-shrink-0 text-amber-600 group-hover:translate-x-1 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>

        </div>

    </div>

    <style>
        .dashboard-card:hover {
            transform: translateY(-2px);
            border-color: #B6934E;
        }
        .dashboard-card {
            border: 1px solid transparent;
        }
    </style>
</x-app-layout>
