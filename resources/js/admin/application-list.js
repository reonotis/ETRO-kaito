/**
 * 申込者一覧 共通スクリプト
 *
 * 銀座(type=1) / 全国(type=2) の一覧画面で共通利用される DataTable・絞り込み・
 * 来場日時 daterangepicker・CSV ダウンロードの初期化処理。
 *
 * 対象の申込フォーム種別は window.Laravel.application_type から取得する。
 */
$(document).ready(function () {

    // ===== 来場日時 daterangepicker 初期化 =====
    initVisitRangePicker();

    function initVisitRangePicker() {
        const $input = $('#search_visit_range');
        if (!$input.length || typeof $.fn.daterangepicker !== 'function') {
            return;
        }

        $input.daterangepicker({
            autoUpdateInput: false,
            opens: 'left',
            locale: {
                format: 'YYYY/MM/DD',
                separator: ' 〜 ',
                applyLabel: '適用',
                cancelLabel: 'クリア',
                fromLabel: '開始',
                toLabel: '終了',
                customRangeLabel: '期間指定',
                weekLabel: 'W',
                daysOfWeek: ['日', '月', '火', '水', '木', '金', '土'],
                monthNames: [
                    '1月', '2月', '3月', '4月', '5月', '6月',
                    '7月', '8月', '9月', '10月', '11月', '12月'
                ],
                firstDay: 0
            }
        });

        $input.on('apply.daterangepicker', function (ev, picker) {
            const from = picker.startDate.format('YYYY-MM-DD');
            const to   = picker.endDate.format('YYYY-MM-DD');
            $input.val(picker.startDate.format('YYYY/MM/DD') + ' 〜 ' + picker.endDate.format('YYYY/MM/DD'));
            $('#search_visit_from').val(from);
            $('#search_visit_to').val(to);
            $('#clear_visit_range').removeClass('hidden').addClass('inline-flex');
            applySearch();
        });

        $input.on('cancel.daterangepicker', function () {
            clearVisitRange();
        });

        $('#clear_visit_range').on('click', function (e) {
            e.stopPropagation();
            clearVisitRange();
        });
    }

    function clearVisitRange() {
        $('#search_visit_range').val('');
        $('#search_visit_from').val('');
        $('#search_visit_to').val('');
        $('#clear_visit_range').addClass('hidden').removeClass('inline-flex');
        applySearch();
    }

    // ===== DataTable 初期化 =====
    let table = $('#application_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.Laravel.route_applications_data,
            data: function (d) {
                d.type              = window.Laravel.application_type;
                d.unique_code       = $('#search_unique_code').val();
                d.name              = $('#search_name').val();
                d.email             = $('#search_email').val();
                d.mail_status       = $('#search_mail_status').val();
                d.visit_from        = $('#search_visit_from').val();
                d.visit_to          = $('#search_visit_to').val();
            }
        },
        pageLength: 20,
        columns: [
            {data: 'created_at', name: 'created_at'},
            {data: 'unique_code', name: 'unique_code'},
            {data: 'name', name: 'name'},
            {data: 'tel', name: 'tel'},
            {data: 'email', name: 'email'},
            {
                data: 'mail_status',
                name: 'mail_status',
                render: function (data) {
                    return renderMailStatus(data);
                }
            },
            {data: 'visit_dates', name: 'visit_dates', orderable: false, searchable: false},
        ],
        order: [[0, 'asc']],
        // テーブル本体・情報・ページネーションのみ表示
        dom: "<'dt-body't><'dt-footer flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-2 py-3'<'dt-info'i><'dt-paginate'p>>",
        language: {
            processing: "読み込み中...",
            lengthMenu: "_MENU_ 件表示",
            zeroRecords: "該当するデータが見つかりません",
            info: "_TOTAL_ 件中 _START_ 件目から _END_ 件目を表示",
            infoEmpty: "該当する申込者がいません",
            infoFiltered: "（全 _MAX_ 件から絞り込み）",
            paginate: {
                first: "最初",
                last: "最後",
                next: "次へ",
                previous: "前へ"
            },
            loadingRecords: "読み込み中...",
            emptyTable: "該当する申込者がいません"
        },
        drawCallback: function (settings) {
            const total = settings.json ? settings.json.recordsFiltered : 0;
            $('#result_count_badge').text((total || 0) + '件');
        }
    });

    // ===== メールステータスをバッジ表示にする =====
    function renderMailStatus(status) {
        if (!status || status === '-') {
            return '<span class="text-gray-400">-</span>';
        }
        if (status === '未確認') {
            return '<span class="status-badge status-badge--pending">未確認</span>';
        }
        if (status === '閲覧済み') {
            return '<span class="status-badge status-badge--done">閲覧済み</span>';
        }
        return '<span class="status-badge">' + $('<div>').text(status).html() + '</span>';
    }

    // ===== 絞り込み条件チップを更新 =====
    function updateFilterChips() {
        const chips = [];

        const code = $('#search_unique_code').val();
        if (code) chips.push({label: '管理番号：' + code, target: '#search_unique_code'});

        const name = $('#search_name').val();
        if (name) chips.push({label: '名前：' + name, target: '#search_name'});

        const email = $('#search_email').val();
        if (email) chips.push({label: 'メール：' + email, target: '#search_email'});

        const mailStatus = $('#search_mail_status').val();
        if (mailStatus) chips.push({label: 'メールステータス：' + mailStatus, target: '#search_mail_status'});

        const vf = $('#search_visit_from').val();
        const vt = $('#search_visit_to').val();
        if (vf || vt) {
            const label = '来場日時：' + (vf || '指定なし') + ' 〜 ' + (vt || '指定なし');
            chips.push({label: label, target: ['#search_visit_from', '#search_visit_to']});
        }

        const $wrap = $('#filter_chips_wrapper');
        const $chips = $('#filter_chips').empty();

        if (chips.length === 0) {
            $wrap.addClass('hidden');
            return;
        }

        chips.forEach(function (c) {
            const $chip = $(
                '<span class="filter-chip">' +
                '  <span>' + $('<div>').text(c.label).html() + '</span>' +
                '  <button type="button" class="filter-chip__close" aria-label="削除">' +
                '    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">' +
                '      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>' +
                '    </svg>' +
                '  </button>' +
                '</span>'
            );
            $chip.find('.filter-chip__close').on('click', function () {
                if (Array.isArray(c.target)) {
                    c.target.forEach(function (t) { $(t).val(''); });
                } else {
                    $(c.target).val('');
                }
                // 来場日時の場合は表示用 input と clear ボタンも隠す
                if (Array.isArray(c.target) && c.target.indexOf('#search_visit_from') !== -1) {
                    $('#search_visit_range').val('');
                    $('#clear_visit_range').addClass('hidden').removeClass('inline-flex');
                }
                applySearch();
            });
            $chips.append($chip);
        });

        $wrap.removeClass('hidden');
    }

    // ===== 検索の実行 =====
    function applySearch() {
        updateFilterChips();
        table.draw();
    }

    // ===== イベントバインド =====
    $('#csv_download').on('click', function () {
        const url = new URL(window.Laravel.route_download_csv, window.location.origin);
        url.searchParams.set('type', window.Laravel.application_type);
        window.location.href = url.toString();
    });

    $('#searchBtn').on('click', applySearch);

    // Enter キーで検索
    $('#search_unique_code, #search_name, #search_email').on('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applySearch();
        }
    });
    $('#search_mail_status').on('change', applySearch);

    $('#resetBtn').on('click', function () {
        $('#search_unique_code').val('');
        $('#search_name').val('');
        $('#search_email').val('');
        $('#search_mail_status').val('');
        // 来場日時はピッカーごとリセット
        $('#search_visit_range').val('');
        $('#search_visit_from').val('');
        $('#search_visit_to').val('');
        $('#clear_visit_range').addClass('hidden').removeClass('inline-flex');
        applySearch();
    });

    $('#clear_filters').on('click', function () {
        $('#resetBtn').trigger('click');
    });

    $('#refresh_btn').on('click', function () {
        table.ajax.reload(null, false);
    });

    // 初期表示時にチップを更新
    updateFilterChips();
});
