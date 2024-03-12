<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['first_name',    'last_name',    'email',    'phone',    'country',    'state',    'city',    'zipcode',    'adresse',    'amount'];
}
