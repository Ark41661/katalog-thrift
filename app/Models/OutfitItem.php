<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutfitItem extends Model
{
    protected $fillable = [
        'outfit_id',
        'product_id',
        'sort_order',
        'note',
        'hotspot_x',
        'hotspot_y',
    ];

    public function outfit()
    {
        return $this->belongsTo(Outfit::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
