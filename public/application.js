
$(document).ready(function() {
    let table = $('#application_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.Laravel.route_applications_data, // Laravel から取得
            data: function(d) {
                d.name = $('#search_name').val();
                d.yomi = $('#search_kana').val();
                d.email = $('#search_email').val();
                d.visit_scheduled_date_time = $('#search_visit_scheduled').val(); // 追加
            }
        },
        pageLength: 20,
        columns: [
            { data: 'created_at', name: 'created_at' },
            { data: 'unique_code', name: 'unique_code' },
            { data: 'name', name: 'name' },
            { data: 'yomi', name: 'yomi' },
            { data: 'sex', name: 'sex' },
            { data: 'age', name: 'age' },
            { data: 'tel', name: 'tel' },
            { data: 'email', name: 'email' },
            { data: 'full_address', name: 'full_address', orderable: false, searchable: false }, // 住所（HTML改行）
            { data: 'visit_scheduled_date_time', name: 'visit_scheduled_date_time' },
            { data: 'status', name: 'status' },
            { data: 'visit_date_time', name: 'visit_date_time' },
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

    /**
     * 当選メール送信
     */
    $('#send_mail').click(function() {
        if (!confirm("当選者にメールを送信しますか？\n一度送信した方へは再送されません")) {
            return;
        }

        $.ajax({
            url: window.Laravel.route_send_mail,
            type: "POST",
            data: {
                _token: window.csrfToken  // CSRFトークンを使用
            },
            success: function (response) {
                alert(response.message);
            },
            error: function () {
                alert("メール送信に失敗しました。");
            }
        });

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
        $('#search_name').val('');
        $('#search_kana').val('');
        $('#search_email').val('');
        $('#search_visit_scheduled').val('');
        table.draw();
    });

});


