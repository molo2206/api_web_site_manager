<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offres extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'Offres';
    protected $fillable =
    [
        'author',
        'title',
        'place',
        'description',
        'startdate',
        'enddate',
        'file',
    ];
    public function author()
    {
        return $this->belongsTo(user::class, 'author', 'id');
    }
}
