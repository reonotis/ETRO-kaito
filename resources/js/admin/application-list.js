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
 *   - DATE_RANGE_PICKERS   : 来場予定日時 / 来場日時 ピッカーの定義
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
    choice1: {data: 'choice_1', name: 'choice_1', class: 'text-center'},
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

        DATE_RANGE_PICKERS.forEach((picker) => setupDateRangePicker(picker, apply));
        setupSearchHandlers(table, apply);
        setupCsvDownload(type);
        setupVisitScheduleUpload(type, table);
        setupSendWinnerMail(type, table);

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
                d.type                 = type;
                d.unique_code          = $('#search_unique_code').val();
                d.name                 = $('#search_name').val();
                d.email                = $('#search_email').val();
                d.mail_status          = $('#search_mail_status').val();
                d.visit_scheduled_from = $('#search_visit_scheduled_from').val();
                d.visit_scheduled_to   = $('#search_visit_scheduled_to').val();
                d.visit_from           = $('#search_visit_from').val();
                d.visit_to             = $('#search_visit_to').val();
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
 * 来場予定日時 / 来場日時 daterangepicker
 * ============================================================ */
const DATE_RANGE_PICKERS = [
    {
        label: '来場予定日時',
        rangeInputId: 'search_visit_scheduled_range',
        fromInputId:  'search_visit_scheduled_from',
        toInputId:    'search_visit_scheduled_to',
        clearBtnId:   'clear_visit_scheduled_range',
    },
    {
        label: '来場日時',
        rangeInputId: 'search_visit_range',
        fromInputId:  'search_visit_from',
        toInputId:    'search_visit_to',
        clearBtnId:   'clear_visit_range',
    },
];

function setupDateRangePicker(picker, apply) {
    const $input = $('#' + picker.rangeInputId);
    if (!$input.length || typeof $.fn.daterangepicker !== 'function') {
        return;
    }

    $input.daterangepicker({
        autoUpdateInput: false,
        opens: 'left',
        locale: DATE_RANGE_LOCALE,
    });

    $input.on('apply.daterangepicker', function (ev, dp) {
        $input.val(dp.startDate.format('YYYY/MM/DD') + ' 〜 ' + dp.endDate.format('YYYY/MM/DD'));
        $('#' + picker.fromInputId).val(dp.startDate.format('YYYY-MM-DD'));
        $('#' + picker.toInputId).val(dp.endDate.format('YYYY-MM-DD'));
        $('#' + picker.clearBtnId).removeClass('hidden').addClass('inline-flex');
        apply();
    });

    $input.on('cancel.daterangepicker', function () {
        clearDateRange(picker, apply);
    });

    $('#' + picker.clearBtnId).on('click', function (e) {
        e.stopPropagation();
        clearDateRange(picker, apply);
    });
}

function clearDateRange(picker, apply) {
    $('#' + picker.rangeInputId).val('');
    $('#' + picker.fromInputId).val('');
    $('#' + picker.toInputId).val('');
    $('#' + picker.clearBtnId).addClass('hidden').removeClass('inline-flex');
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
        DATE_RANGE_PICKERS.forEach((picker) => {
            $('#' + picker.rangeInputId + ', #' + picker.fromInputId + ', #' + picker.toInputId).val('');
            $('#' + picker.clearBtnId).addClass('hidden').removeClass('inline-flex');
        });
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

    DATE_RANGE_PICKERS.forEach((picker) => {
        const from = $('#' + picker.fromInputId).val();
        const to   = $('#' + picker.toInputId).val();
        if (from || to) {
            chips.push({
                label: picker.label + '：' + (from || '指定なし') + ' 〜 ' + (to || '指定なし'),
                picker: picker,
            });
        }
    });

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
        // 日付レンジ系のチップは picker 経由でまとめてクリアする
        if (chip.picker) {
            clearDateRange(chip.picker, apply);
            return;
        }

        const targets = Array.isArray(chip.target) ? chip.target : [chip.target];
        targets.forEach(function (t) { $(t).val(''); });
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

/* ============================================================
 * 来場予定日時 一括更新（CSVアップロード）
 * ============================================================ */
function setupVisitScheduleUpload(type, table) {
    const $btn = $('#visit_schedule_upload_btn');
    const $input = $('#visit_schedule_file_input');

    if (!$btn.length || !$input.length) {
        return;
    }

    $btn.on('click', function () {
        $input.val('');
        $input.trigger('click');
    });

    $input.on('change', function () {
        const file = this.files && this.files[0];
        if (!file) {
            return;
        }

        const formData = new FormData();
        formData.append('type', type);
        formData.append('csv_file', file);

        $btn.prop('disabled', true).text('更新中...');

        fetch(window.Laravel.route_update_visit_schedule, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': window.csrfToken },
            body: formData,
        })
            .then((res) => res.json())
            .then((data) => {
                let message = `${data.updated}件の来場予定日時を更新しました。`;
                if (data.errors && data.errors.length) {
                    message += `\n\n以下の${data.errors.length}件は更新できませんでした:\n` + data.errors.join('\n');
                }
                alert(message);
                table.ajax.reload(null, false);
            })
            .catch(() => {
                alert('更新に失敗しました。ファイルの形式をご確認のうえ、再度お試しください。');
            })
            .finally(() => {
                $btn.prop('disabled', false).text('来場予定日時を更新');
            });
    });
}

/* ============================================================
 * 未送信者への当選通知メール一斉送信
 * ============================================================ */
function setupSendWinnerMail(type, table) {
    const $btn = $('#send_winner_mail_btn');

    if (!$btn.length) {
        return;
    }

    $btn.on('click', function () {
        if (!confirm('「未送信」の申込者へ当選通知メールを一斉送信します。よろしいですか？')) {
            return;
        }

        $btn.prop('disabled', true).text('送信中...');

        fetch(window.Laravel.route_send_winner_mail, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ type }),
        })
            .then((res) => res.json())
            .then((data) => {
                let message = `${data.sent}件に当選通知メールを送信しました。`;
                if (data.errors && data.errors.length) {
                    message += `\n\n以下の${data.errors.length}件は送信できませんでした:\n` + data.errors.join('\n');
                }
                alert(message);
                table.ajax.reload(null, false);
            })
            .catch(() => {
                alert('送信に失敗しました。時間をおいて再度お試しください。');
            })
            .finally(() => {
                $btn.prop('disabled', false).text('未送信者へ一斉送信');
            });
    });
}
