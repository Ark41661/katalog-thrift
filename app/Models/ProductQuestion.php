<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductQuestion extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'question',
        'answer', 'answered_at', 'answered_by',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answerer()
    {
        return $this->belongsTo(User::class, 'answered_by');
    }
}
