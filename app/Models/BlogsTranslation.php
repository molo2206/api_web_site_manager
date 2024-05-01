<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogsTranslation extends Model
{
    use HasFactory;
    protected $table ="blogs_translation";
    public $timestamps = false;
    protected $fillable = ["blogs_id","title","locale","description","documentation"];
}
