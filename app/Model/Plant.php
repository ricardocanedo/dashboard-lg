<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plant extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function productionLines()
    {
        return $this->hasMany(ProductionLine::class);
    }
}
