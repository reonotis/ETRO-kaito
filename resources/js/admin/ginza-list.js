/**
 * 銀座フォーム（type=1）の申込者一覧画面エントリポイント。
 */
import { COLUMNS, initApplicationList } from './application-list.js';

initApplicationList({
    type: 1, // CommonConst::APPLICATION_TYPE_1（銀座）
    columns: [
        COLUMNS.createdAt,
        COLUMNS.uniqueCode,
        COLUMNS.name,
        COLUMNS.tel,
        COLUMNS.email,
        COLUMNS.mailStatus,
        COLUMNS.visitScheduledDateTime,
        COLUMNS.visitDateTime,
    ],
});
