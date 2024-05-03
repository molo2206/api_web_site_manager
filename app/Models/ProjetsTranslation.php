<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjetsTranslation extends Model
{
    use HasFactory;
    protected $table ="projetstranslation";
    public $timestamps = false;
    protected $fillable = ["projetid","title","locale"];
}
