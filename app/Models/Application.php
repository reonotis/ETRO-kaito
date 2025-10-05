<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $tel
 * @property string $email
 * @property string $address
 * @property string $unique_code
 * @property Carbon $created_at
 * @property Carbon $updated_at
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
        'name',
        'tel',
        'email',
        'address',
        'unique_code',
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

    /**
     * @return HasMany
     */
    public function targetEvents(): HasMany
    {
        return $this->hasMany(TargetEvent::class);
    }

    /**
     * @return HasMany
     */
    public function visited(): HasMany
    {
        return $this->hasMany(Visited::class);
    }

}
