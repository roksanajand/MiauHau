<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Cat;
use App\Models\Dog;

class GuestAndLoggedPhotoCarouselTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Przygotowanie danych dla testów.
     */
    protected function prepareDogData(): void
    {
        // Setup: Utworzenie i logowanie użytkownika
        $user = User::factory()->create();
        $this->actingAs($user);

        // Przygotowanie danych zwierzaka
        $dog = Cat::create(['breed' => 'Golden Retriever']);

        // Dane formularza z dodatkowymi warunkami
        $data = [
            'name' => 'Daisy',
            'type' => 'dog',
            'breed_id' => $dog->id,
            'age' => 5,
            'description' => 'Przyjazny',
            'country' => 'Polska',
            'city' => 'Warszawa',
            'c_black' => 1,
            'c_white' => 0,
            'c_ginger' => 1, // Zmienione cechy kolorów
            'c_tricolor' => 1,
            'c_grey' => 0,
            'c_brown' => 0,
            'c_golden' => 0,
            'c_other' => 1,
            'isApproved' => 'yes',
            '_token' => csrf_token(),
        ];

        // Akcja: Dodanie zwierzaka
        $response = $this->post(route('animals.store'), $data);

        // Aserty: Sprawdzenie przekierowania i poprawności danych w bazie
        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('animals', [
            'name' => 'Daisy',
            'type' => 'dog',
            'breed_id' => $dog->id,
            'age' => 5,
            'description' => 'Przyjazny',
            'country' => 'Polska',
            'city' => 'Warszawa',
            'c_black' => 1,
            'c_ginger' => 1,
            'c_tricolor' => 1,
            'c_other' => 1,
        ]);

        \App\Models\Animal::query()
            ->where('isApproved', 'waiting')
            ->update(['isApproved' => 'yes']);

    }
    public function test_guest_user_photo_visibility(): void
    {
        $this->prepareDogData();
        $this->post(route('logout'));
        // Symulacja użytkownika gościa (niezalogowanego)
        $response = $this->get('/'); // Załóżmy, że karuzela jest na głównej stronie

        // Sprawdzenie czy nie ma zdjęć 'lapka.jpg'
        $response->assertDontSee('lapka.jpg');

        // Sprawdzenie, że nie są wyświetlane guziki "Lubię to"
        $response->assertDontSee('Lubię to');

        // Sprawdzenie, że nie są wyświetlane dane zwierząt: imię, miasto, email
        $response->assertDontSee('Imię:');
        $response->assertDontSee('Miasto:');
        $response->assertDontSee('Kontakt do właściciela:');

        // Sprawdzenie, czy wyświetlają się strzałki nawigacyjne karuzeli
        $response->assertSee('arrow left');
        $response->assertSee('arrow right');
    }

    public function test_authenticated_user_photo_visibility(): void
    {
        $this->prepareDogData();

        $response = $this->get('/');

        // Sprawdzenie, że są wyświetlane guziki "Lubię to"
        $response->assertSee('Lubię to');

        // Sprawdzenie czy wyświetlane są zdjęcia 'lapka.jpg'
        $response->assertSee('lapka.jpg');

        // Sprawdzenie, że są wyświetlane dane zwierząt: imię, miasto, email
        $response->assertSee('Imię:');
        $response->assertSee('Miasto:');
        $response->assertSee('Kontakt do właściciela:');

        // Sprawdzenie, czy wyświetlają się strzałki nawigacyjne karuzeli
        $response->assertSee('arrow left');
        $response->assertSee('arrow right');

    }
}
