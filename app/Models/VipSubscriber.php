<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VipSubscriber extends Model
{
    protected $fillable = ['email', 'name', 'token', 'is_active', 'confirmed_at'];
    protected $casts    = ['is_active' => 'boolean', 'confirmed_at' => 'datetime'];

    protected static function booted(): void
    {
        static::creating(function (VipSubscriber $sub) {
            if (empty($sub->token)) {
                $sub->token = Str::random(32);
            }
        });
    }
}
