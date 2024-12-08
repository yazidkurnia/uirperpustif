<?php

namespace App\Models\Collager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collager extends Model
{
    use HasFactory;
    protected $fillable = ['npm', 'nama', 'email'];
}
