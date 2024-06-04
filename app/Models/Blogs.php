<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Blogs extends Model
{
    use HasFactory, HasUuids, Translatable;
    protected $fillable = ['category_id',"publication_date","image","author","status","deleted"];
    public $translatedAttributes = ["title","description","documentation"];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function author(){
        return $this->belongsTo(Team::class, 'author','id');
    }
}
