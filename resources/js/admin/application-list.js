/**
 * 申込者一覧画面 共通スクリプト
 *
 * 銀座 / 全国の各エントリ（ginza-list.js / all-store-list.js）から呼び出される。
 * エントリ側は type と表示する列だけを宣言すればよい。
 *
 * 構成：
 *   - COLUMNS              : 表示できる列の定義（エントリで並べて使う）
 *   - initApplicationList  : 画面全体の初期化エントリ
 *   - createTable          : DataTable の構築
 *   - setupDateRangePicker : 来場日時ピッカー
 *   - setupSearchHandlers  : 検索ボタン・Enterキー・リセット等のバインド
 *   - updateFilterChips    : 絞り込みチップ表示
 *   - setupCsvDownload     : CSV ダウンロードボタン
 */

/* ============================================================
 * 列定義（エントリ側から並べて使う）
 * ============================================================ */
export const COLUMNS = {
    createdAt: {data: 'created_at', name: 'created_at'},
    storeName: {data: 'store_name', name: 'store_name', orderable: false, searchable: false},
    uniqueCode: {data: 'unique_code', name: 'unique_code'},
    name: {data: 'name', name: 'name'},
    tel: {data: 'tel', name: 'tel'},
    email: {data: 'email', name: 'email'},
    mailStatus: {data: 'mail_status', name: 'mail_status', class: 'text-center', render: renderMailStatus},
    visitScheduledDateTime: {
        data: 'visit_scheduled_date_time',
        name: 'visit_scheduled_date_time',
        searchable: false
    },
    visitDateTime: {data: 'visit_date_time', name: 'visit_date_time', searchable: false},
};

/* ============================================================
 * 初期化エントリ
 * ============================================================ */
/**
 * @param {Object} options
 * @param {number} options.type    - 申込フォーム種別（CommonConst::APPLICATION_TYPE_*）
 * @param {Array}  options.columns - 表示する列（COLUMNS の値を並べる）
 */
export function initApplicationList({ type, columns }) {
    if (!type) {
        throw new Error('initApplicationList: type is required');
    }
    if (!columns || !columns.length) {
        throw new Error('initApplicationList: columns is required');
    }

    $(document).ready(function () {
        const table = createTable(type, columns);
        const apply = () => {
            updateFilterChips(apply);
            table.draw();
        };

        setupDateRangePicker(apply);
        setupSearchHandlers(table, apply);
        setupCsvDownload(type);

        // 初期表示時のチップ
        updateFilterChips(apply);
    });
}

/* ============================================================
 * DataTable
 * ============================================================ */
function createTable(type, columns) {
    return $('#application_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.Laravel.route_applications_data,
            data: (d) => {
                d.type        = type;
                d.unique_code = $('#search_unique_code').val();
                d.name        = $('#search_name').val();
                d.email       = $('#search_email').val();
                d.mail_status = $('#search_mail_status').val();
                d.visit_from  = $('#search_visit_from').val();
                d.visit_to    = $('#search_visit_to').val();
            },
        },
        columns: columns,
        pageLength: 20,
        order: [[0, 'asc']],
        dom: "<'dt-body't><'dt-footer flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-2 py-3'<'dt-info'i><'dt-paginate'p>>",
        language: TABLE_LANGUAGE,
        drawCallback: function (settings) {
            const total = settings.json ? settings.json.recordsFiltered : 0;
            $('#result_count_badge').text((total || 0) + '件');
        },
    });
}

const TABLE_LANGUAGE = {
    processing:     '読み込み中...',
    lengthMenu:     '_MENU_ 件表示',
    zeroRecords:    '該当するデータが見つかりません',
    info:           '_TOTAL_ 件中 _START_ 件目から _END_ 件目を表示',
    infoEmpty:      '該当する申込者がいません',
    infoFiltered:   '（全 _MAX_ 件から絞り込み）',
    paginate:       { first: '最初', last: '最後', next: '次へ', previous: '前へ' },
    loadingRecords: '読み込み中...',
    emptyTable:     '該当する申込者がいません',
};

/* ============================================================
 * メールステータス バッジ
 * ============================================================ */
function renderMailStatus(status) {
    if (!status || status === '-') {
        return '<span class="text-gray-400">-</span>';
    }
    if (status === '未送信') {
        return '<span class="status-badge status-badge--pending">未送信</span>';
    }
    if (status === '未確認') {
        return '<span class="status-badge status-badge--none-check">未確認</span>';
    }
    if (status === '開封済み') {
        return '<span class="status-badge status-badge--done">開封済み</span>';
    }
    return '<span class="status-badge">' + $('<div>').text(status).html() + '</span>';
}


/* ============================================================
 * 来場日時 daterangepicker
 * ============================================================ */
function setupDateRangePicker(apply) {
    const $input = $('#search_visit_range');
    if (!$input.length || typeof $.fn.daterangepicker !== 'function') {
        return;
    }

    $input.daterangepicker({
        autoUpdateInput: false,
        opens: 'left',
        locale: DATE_RANGE_LOCALE,
    });

    $input.on('apply.daterangepicker', function (ev, picker) {
        $input.val(picker.startDate.format('YYYY/MM/DD') + ' 〜 ' + picker.endDate.format('YYYY/MM/DD'));
        $('#search_visit_from').val(picker.startDate.format('YYYY-MM-DD'));
        $('#search_visit_to').val(picker.endDate.format('YYYY-MM-DD'));
        $('#clear_visit_range').removeClass('hidden').addClass('inline-flex');
        apply();
    });

    $input.on('cancel.daterangepicker', function () {
        clearVisitRange(apply);
    });

    $('#clear_visit_range').on('click', function (e) {
        e.stopPropagation();
        clearVisitRange(apply);
    });
}

function clearVisitRange(apply) {
    $('#search_visit_range').val('');
    $('#search_visit_from').val('');
    $('#search_visit_to').val('');
    $('#clear_visit_range').addClass('hidden').removeClass('inline-flex');
    apply();
}

const DATE_RANGE_LOCALE = {
    format: 'YYYY/MM/DD',
    separator: ' 〜 ',
    applyLabel: '適用',
    cancelLabel: 'クリア',
    fromLabel: '開始',
    toLabel: '終了',
    customRangeLabel: '期間指定',
    weekLabel: 'W',
    daysOfWeek: ['日', '月', '火', '水', '木', '金', '土'],
    monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
    firstDay: 0,
};

/* ============================================================
 * 検索ボタン・リセット・リフレッシュのバインド
 * ============================================================ */
function setupSearchHandlers(table, apply) {
    $('#searchBtn').on('click', apply);
    $('#search_mail_status').on('change', apply);

    // Enter で検索
    $('#search_unique_code, #search_name, #search_email').on('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            apply();
        }
    });

    $('#resetBtn').on('click', function () {
        $('#search_unique_code, #search_name, #search_email, #search_mail_status').val('');
        $('#search_visit_range, #search_visit_from, #search_visit_to').val('');
        $('#clear_visit_range').addClass('hidden').removeClass('inline-flex');
        apply();
    });

    $('#clear_filters').on('click', function () {
        $('#resetBtn').trigger('click');
    });

    $('#refresh_btn').on('click', function () {
        table.ajax.reload(null, false);
    });
}

/* ============================================================
 * 絞り込みチップ表示
 * ============================================================ */
function updateFilterChips(apply) {
    const chips = collectActiveFilters();
    const $wrap  = $('#filter_chips_wrapper');
    const $chips = $('#filter_chips').empty();

    if (chips.length === 0) {
        $wrap.addClass('hidden');
        return;
    }

    chips.forEach(function (chip) {
        $chips.append(buildChipElement(chip, apply));
    });
    $wrap.removeClass('hidden');
}

function collectActiveFilters() {
    const chips = [];

    const code = $('#search_unique_code').val();
    if (code) chips.push({ label: '管理番号：' + code, target: '#search_unique_code' });

    const name = $('#search_name').val();
    if (name) chips.push({ label: '名前：' + name, target: '#search_name' });

    const email = $('#search_email').val();
    if (email) chips.push({ label: 'メール：' + email, target: '#search_email' });

    const mailStatus = $('#search_mail_status').val();
    if (mailStatus) chips.push({ label: 'メールステータス：' + mailStatus, target: '#search_mail_status' });

    const vf = $('#search_visit_from').val();
    const vt = $('#search_visit_to').val();
    if (vf || vt) {
        chips.push({
            label: '来場日時：' + (vf || '指定なし') + ' 〜 ' + (vt || '指定なし'),
            target: ['#search_visit_from', '#search_visit_to'],
        });
    }

    return chips;
}

function buildChipElement(chip, apply) {
    const $chip = $(
        '<span class="filter-chip">' +
        '  <span>' + $('<div>').text(chip.label).html() + '</span>' +
        '  <button type="button" class="filter-chip__close" aria-label="削除">' +
        '    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">' +
        '      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>' +
        '    </svg>' +
        '  </button>' +
        '</span>'
    );

    $chip.find('.filter-chip__close').on('click', function () {
        const targets = Array.isArray(chip.target) ? chip.target : [chip.target];
        targets.forEach(function (t) { $(t).val(''); });

        // 来場日時の場合は表示用 input と clear ボタンも隠す
        if (targets.indexOf('#search_visit_from') !== -1) {
            $('#search_visit_range').val('');
            $('#clear_visit_range').addClass('hidden').removeClass('inline-flex');
        }
        apply();
    });

    return $chip;
}

/* ============================================================
 * CSV ダウンロード
 * ============================================================ */
function setupCsvDownload(type) {
    $('#csv_download').on('click', function () {
        const url = new URL(window.Laravel.route_download_csv, window.location.origin);
        url.searchParams.set('type', type);
        window.location.href = url.toString();
    });
}
