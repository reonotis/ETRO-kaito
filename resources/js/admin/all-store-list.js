/**
 * 全国フォーム（type=2）の申込者一覧画面エントリポイント。
 * 全国フォームは来場店舗の選択があるため、申込日時の直後に storeName 列を入れる。
 */
import { COLUMNS, initApplicationList } from './application-list.js';

initApplicationList({
    type: 2, // CommonConst::APPLICATION_TYPE_2（全国）
    columns: [
        COLUMNS.createdAt,
        COLUMNS.storeName, // 全国専用：来場店舗
        COLUMNS.uniqueCode,
        COLUMNS.name,
        COLUMNS.tel,
        COLUMNS.email,
        COLUMNS.mailStatus,
        COLUMNS.visitScheduledDateTime,
        COLUMNS.visitDateTime,
    ],
});
