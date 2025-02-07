<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $breed
 */
class Dog extends Model
{
    protected $fillable = ['breed'];
}
