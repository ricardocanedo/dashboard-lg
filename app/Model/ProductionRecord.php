<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'production_line_id',
        'production_date',
        'good_parts',
        'defective_parts',
        'efficiency',
    ];

    public function productionLine()
    {
        return $this->belongsTo(ProductionLine::class);
    }
}
