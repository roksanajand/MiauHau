<?php

namespace Tests\Feature\Auth;

use App\Models\Animal;
use App\Models\Cat;
use App\Models\Dog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddAnimalTest extends TestCase
{
    use RefreshDatabase;


    public function test_add_animal_form_requires_authentication(): void
    {
        // Niezalogowany użytkownik
        $response = $this->get('/add-animal');

        // Powinien zostać przekierowany do strony logowania
        $response->assertRedirect('/login');
    }


    public function test_add_animal_form_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/add-animal');

        $response->assertStatus(200);
        $response->assertSee('Dodaj Nowe Zwierzę');
    }


    public function test_users_can_add_an_animal(): void
    {
        // Tworzenie użytkownika i logowanie
        $user = User::factory()->create();
        $this->actingAs($user);

        // Tworzenie ras zwierząt
        $cat = Cat::create(['breed' => 'Siberian']);
        $dog = Dog::create(['breed' => 'Golden Retriever']);

        // Dane formularza
        $data = [
            'name' => 'Burek',
            'type' => 'dog',
            'breed_id' => $dog->id,
            'age' => 5,
            'description' => 'Przyjazny pies.',
            'country' => 'Polska',
            'city' => 'Warszawa',
            'c_black' => 1, // Kolory jako pola osobne
            'c_white' => 1,
            'c_ginger' => 0,
            'c_tricolor' => 0,
            'c_grey' => 0,
            'c_brown' => 0,
            'c_golden' => 0,
            'c_other' => 0,
            '_token' => csrf_token(), // Token CSRF
        ];


        $response = $this->post(route('animals.store'), $data);


        $response->assertRedirect(route('dashboard'));


        $this->assertDatabaseHas('animals', [
            'name' => 'Burek',
            'type' => 'dog',
            'breed_id' => $dog->id,
            'age' => 5,
            'description' => 'Przyjazny pies.',
             'country' => 'Polska',
            'city' => 'Warszawa',
            'c_black' => 1,
            'c_white' => 1,
        ]);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        // Sprawdzenie, że widoczne są dane d
        $response->assertSee('Golden Retriever');
        $response->assertSee('Przyjazny pies.');
        $response->assertSee('Polska');
        $response->assertSee('Warszawa');

        // Sprawdzenie wyświetlanych kolorów
        $response->assertSee('Czarny'); // Kolor black
        $response->assertSee('Biały');  // Kolor white

        // Sprawdzenie, że inne kolory nie są wyświetlane
        $response->assertDontSee('Rudy');
        $response->assertDontSee('Szary');


    }



}
