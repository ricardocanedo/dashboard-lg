<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'production_line_id',
        'date',
        'good_parts',
        'defective_parts',
        'efficiency',
    ];
}
