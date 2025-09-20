<?php

namespace App\Consts;

class Common
{
    public const SEX_MEN = 1;
    public const SEX_WOMAN = 2;
    public const SEX_OTHER = 3;
    public const SEX_LIST = [
        self::SEX_MEN => '男性(Man)',
        self::SEX_WOMAN => '女性(Woman)',
        self::SEX_OTHER => 'その他(Other)',
    ];

    public const TARGET_DATE_LIST = [
        1 => '10/4 展示会 (Exhibition)',
        2 => '10/4 レセプション (Reception)',
        3 => '10/5 展示会 (Exhibition)',
    ];
}

