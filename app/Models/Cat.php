<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $breed
 */
class Cat extends Model
{
    protected $fillable = ['breed'];
}
