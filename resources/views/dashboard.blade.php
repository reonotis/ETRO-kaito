<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            申込者一覧
        </h2>
    </x-slot>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

    @vite(['resources/css/admin.css'])
    <script>
        window.Laravel = {
            route_download_csv: "{{ route('applications.download-csv') }}",
            route_applications_data: "{{ route('applications.data') }}",
        };

        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="./application.js?v=3"></script>

    <div class="py-12 px-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between mb-2">
                    <button id="csv_download" class="btn btn-primary">全件CSVダウンロード</button>
                </div>

                <div class="my-2 flex items-center gap-1">
                    <input type="text" id="search_unique_code" class="border-gray-300 rounded-md" placeholder="管理番号で検索">
                    <input type="text" id="search_name" class="border-gray-300 rounded-md" placeholder="名前で検索">
                    <input type="text" id="search_email" class="border-gray-300 rounded-md" placeholder="メールで検索">
                    <div>
                        <button id="searchBtn" class="btn btn-primary">検索</button>
                        <button id="resetBtn" class="btn btn-secondary">リセット</button>
                    </div>
                </div>

                <table id="application_table" class="min-w-full bg-white border">
                    <thead>
                    <tr>
                        <th>申込日時</th>
                        <th>管理番号</th>
                        <th>名前</th>
                        <th>電話番号</th>
                        <th>メールアドレス</th>
                        <th>住所</th>
                        <th>10/4 展示会</th>
                        <th>10/4 レセプション</th>
                        <th>10/5</th>
                        <th>メールステータス</th>
                        <th>来場日時</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
