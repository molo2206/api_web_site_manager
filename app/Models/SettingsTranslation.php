<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table='setting_translations';
    protected $fillable = ["about_us","mission","vision","history","values"];
}
