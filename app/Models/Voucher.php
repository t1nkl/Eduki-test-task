<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $discount
 * @property string $code
 */
class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount',
    ];
    protected $casts = [
        'discount' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (self $voucher) {
            $voucher->code = strtoupper(Str::random(8));
        });
    }
}
