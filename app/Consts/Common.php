<?php

namespace App\Consts;

class Common
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
}

