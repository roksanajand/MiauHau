<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Footer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FooterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Sprawdza, czy metoda getAdminContactInfo() poprawnie zwraca dane administratora.
     *
     * @return void
     */
    public function test_get_admin_contact_info_returns_admin_user(): void
    {
        // Przygotowanie danych w tabeli users
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'adminStrony@gmail.com',
        ]);

        // Pobranie danych administratora
        $adminContact = Footer::getAdminContactInfo();

        // Assercje
        $this->assertNotNull($adminContact);
        $this->assertInstanceOf(User::class, $adminContact);
        $this->assertEquals('Admin', $adminContact->name);
        $this->assertEquals('adminStrony@gmail.com', $adminContact->email);
    }

    /**
     * Sprawdza, czy metoda getAdminContactInfo() zwraca null, gdy brak użytkownika Admin.
     *
     * @return void
     */
    public function test_get_admin_contact_info_returns_null_when_no_admin(): void
    {
        // Pobranie danych administratora bez wcześniejszego dodania rekordu
        $adminContact = Footer::getAdminContactInfo();

        // Assercja
        $this->assertNull($adminContact); // Upewnij się, że zwrócono null
    }
}
