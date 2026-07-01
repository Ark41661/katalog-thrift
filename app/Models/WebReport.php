<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebReport extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email', 'category', 'message',
        'status', 'resolved_by', 'resolution_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
