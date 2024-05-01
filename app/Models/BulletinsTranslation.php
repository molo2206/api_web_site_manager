<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinsTranslation extends Model
{
    use HasFactory;
    protected $table ="bulletintranslation";
    public $timestamps = false;
    protected $fillable = ["bulletins_id","title","locale","description","year","month","created"];
}
