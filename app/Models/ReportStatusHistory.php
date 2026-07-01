<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportStatusHistory extends Model
{
    protected $table = 'report_status_history';

    protected $fillable = [
        'product_report_id',
        'status',
        'action_by',
        'note',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function report()
    {
        return $this->belongsTo(ProductReport::class, 'product_report_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
