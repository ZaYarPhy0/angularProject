<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleArea extends Model
{
    use HasFactory;
    protected $guarded=[];
    private function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
}
