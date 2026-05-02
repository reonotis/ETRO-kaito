<?php

namespace App\Consts;

class StoreAllConst
{
    public const SEX_MEN = 1;
    public const SEX_WOMAN = 2;
    public const SEX_OTHER = 3;
    public const SEX_LIST = [
        self::SEX_MEN => '男性',
        self::SEX_WOMAN => '女性',
        self::SEX_OTHER => 'その他',
        4 => '未回答',
    ];

    public const STORE_A = 1;
    public const STORE_B = 2;
    public const STORE_C = 3;
    public const STORE_D = 4;
    public const STORE_E = 5;
    public const STORE_F = 6;
    public const STORE_G = 7;
    public const STORE_H = 8;
    public const STORE_LIST = [
        self::STORE_A => '新宿',
        self::STORE_B => '渋谷',
        self::STORE_C => '池袋',
        self::STORE_D => '店舗名',
        self::STORE_E => '店舗名',
        self::STORE_F => '店舗名',
        self::STORE_G => '店舗名',
        self::STORE_H => '店舗名',
    ];

}

