/**
 * 阪急フォーム（type=2）の申込者一覧画面エントリポイント。
 */
import { COLUMNS, initApplicationList } from './application-list.js';

initApplicationList({
    type: 2, // CommonConst::APPLICATION_TYPE_2（阪急）
    columns: [
        COLUMNS.createdAt,
        COLUMNS.uniqueCode,
        COLUMNS.name,
        COLUMNS.tel,
        COLUMNS.email,
        COLUMNS.mailStatus,
        COLUMNS.choice1,
        COLUMNS.visitScheduledDateTime,
        COLUMNS.visitDateTime,
    ],
});
