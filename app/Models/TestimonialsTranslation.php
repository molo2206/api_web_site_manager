<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestimonialsTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table="testimonial_translations";
    protected $fillable = ["message","fonction"];
}
