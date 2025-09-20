
$(document).ready(function() {
    let table = $('#application_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.Laravel.route_applications_data, // Laravel から取得
            data: function(d) {
                d.unique_code = $('#search_unique_code').val();
                d.name = $('#search_name').val();
                d.email = $('#search_email').val();
            }
        },
        pageLength: 20,
        columns: [
            { data: 'created_at', name: 'created_at' },
            { data: 'unique_code', name: 'unique_code' },
            { data: 'name', name: 'name' },
            { data: 'tel', name: 'tel' },
            { data: 'email', name: 'email' },
            { data: 'address', name: 'address', orderable: false, searchable: false },
            { data: 'date_1', name: 'date_1' },
            { data: 'date_2', name: 'date_2' },
            { data: 'date_3', name: 'date_3' },
            { data: 'mail_status', name: 'mail_status' },
            { data: 'visit_dates', name: 'visit_dates', orderable: false, searchable: false },
        ],
        order: [[0, 'asc']], // 初期並び順：ID昇順
        dom: "<'row'<'col-sm-12'tr>>" + // 検索ボックスを非表示
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        language: {
            processing: "読み込み中...",
            lengthMenu: "_MENU_ 件表示",
            zeroRecords: "該当するデータが見つかりません",
            info: "全 _TOTAL_ 件中 _START_ から _END_ まで表示",
            infoEmpty: "データがありません",
            infoFiltered: "（全 _MAX_ 件から絞り込み）",
            search: "検索:",
            paginate: {
                first: "最初",
                last: "最後",
                next: "次",
                previous: "前"
            },
            loadingRecords: "読み込み中...",
            emptyTable: "テーブルにデータがありません"
        },
    });

    // CSVダウンロード
    $('#csv_download').click(function() {
        window.location.href = window.Laravel.route_download_csv;
    });

    // 検索ボタンで検索実行
    $('#searchBtn').click(function() {
        table.draw();
    });

    // リセットボタンで検索リセット
    $('#resetBtn').click(function() {
        $('#search_unique_code').val('');
        $('#search_name').val('');
        $('#search_email').val('');
        table.draw();
    });

});


