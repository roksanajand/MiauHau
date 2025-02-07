<?php

namespace App\Http\Controllers;

use App\Models\Footer;

class FooterController extends Controller
{
    /**
     * Wyświetla widok stopki.
     *
     * @return \Illuminate\View\View
     */
    public function show(): \Illuminate\View\View
    {
        // Pobierz dane użytkownika "Admin" z bazy
        $adminContact = Footer::getAdminContactInfo();

        if (!$adminContact) {
            abort(404, 'Administrator not found');
        }

        // Przekazanie do widoku
        return view('components.footer', [
            'contactInfo' => [
                'name' => $adminContact->name,
                'email' => $adminContact->email,
            ],
            'regulaminPath' => 'pliki_pdf/Regulamin.pdf',
        ]);
    }
}
