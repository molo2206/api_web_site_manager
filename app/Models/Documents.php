<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'Rapports';
    protected $fillable =
    [
        'author',
        'title',
        'description',
        'created',
        'file',
    ];
    public function author()
    {
        return $this->belongsTo(user::class, 'author', 'id');
    }
}
