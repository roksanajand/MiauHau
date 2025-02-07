<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Footer extends Model
{
    // Definicja tabeli
    protected $table = 'footer_data';

    // Definicja pól, które można masowo przypisywać
    protected $fillable = ['name', 'email', 'phone', 'regulamin_path'];

    // Wyłączenie automatycznych timestampów
    public $timestamps = false;

    /**
     * Pobiera dane kontaktowe administratora z tabeli `users`.
     *
     * @return User|null
     */
    public static function getAdminContactInfo(): ?\App\Models\User
    {
        return \App\Models\User::where('name', 'Admin')->first();
    }
}
