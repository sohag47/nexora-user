<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    public function profile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot('grade')->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
