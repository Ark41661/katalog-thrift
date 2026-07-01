<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutfitSave extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id', 'outfit_id'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public function user()   { return $this->belongsTo(User::class); }
    public function outfit() { return $this->belongsTo(Outfit::class); }
}
