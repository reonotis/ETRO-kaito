<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property Carbon $created_at
 *
 */
class Visited extends Model
{
    use HasFactory;

    protected $table = 'visited';
    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'application_id',
    ];

    protected $guarded = ['id']; // ID と _token は保存不可

}
