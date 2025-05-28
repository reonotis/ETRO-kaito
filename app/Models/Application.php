<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $sei
 * @property string $mei
 * @property string $sei_kana
 * @property string $mei_kana
 * @property string $email
 * @property Carbon $visit_scheduled_date_time
 * @property Carbon $visit_date_time
 *
 */
class Application extends Model
{
    use HasFactory;

    protected $table = 'application';
    protected $casts = [
        'visit_scheduled_date_time' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'sei',
        'mei',
        'sei_kana',
        'mei_kana',
        'age',
        'sex',
        'tel',
        'email',
        'zip21',
        'zip22',
        'pref21',
        'address21',
        'street21',
        'sent_lottery_result_email_flg',
        'visit_date_time',
    ];

    protected $guarded = ['id', '_token']; // ID と _token は保存不可

    /**
     * 登録時に自動でユニークコードを生成
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($application) {
            $application->unique_code = self::generateUniqueCode();
        });
    }

    /**
     * ユニークコードを生成するメソッド
     * @return string
     */
    private static function generateUniqueCode(): string
    {
        do {
            $code = Str::random(10);
        } while (self::where('unique_code', $code)->exists());

        return $code;
    }


}
