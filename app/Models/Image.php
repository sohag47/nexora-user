<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasAuditFields, HasFactory;

    public function imageable()
    {
        return $this->morphTo();
    }
}
