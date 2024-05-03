<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommuniquesTranslation extends Model
{
    use HasFactory;
    protected $table = "communiquestranslation";
    public $timestamps = false;
    protected $fillable = ["communiqueid", "title", "description", "locale"];
}
