<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionLine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'plant_id'
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function productionRecords()
    {
        return $this->hasMany(ProductionRecord::class);
    }
}
