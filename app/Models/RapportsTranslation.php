<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapportsTranslation extends Model
{
    use HasFactory;
    protected $table = "rapportstranslation";
    public $timestamps = false;
    protected $fillable = ["rapportid","description","title","locale"];
}
