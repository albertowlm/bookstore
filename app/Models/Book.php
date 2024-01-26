<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'publishing_companies',
        'edition',
        'year_publication',
        'price',
    ];
    public function authors()
    {
        return $this->belongsToMany(Author::class,'book_authors');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class,'book_subjects');
    }

}
