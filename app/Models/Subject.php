<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'description'
    ];
    public function books()
    {
        return $this->belongsToMany(Book::class,'book_subjects');
    }
}
