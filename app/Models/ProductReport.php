<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReport extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'reason',
        'detail',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function history()
    {
        return $this->hasMany(ReportStatusHistory::class, 'product_report_id')->oldest();
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
