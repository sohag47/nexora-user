<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class)->withPivot('grade')->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
