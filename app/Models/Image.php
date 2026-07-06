<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasAuditFields, HasFactory, SoftDeletes;

    protected $guarded = [];

    public function imageable()
    {
        return $this->morphTo();
    }
}
