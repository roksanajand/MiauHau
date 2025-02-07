<?php

namespace Tests\Feature\Auth;

use App\Models\Animal;
use App\Models\Dog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditAnimalTest extends TestCase
{
    use RefreshDatabase;

    // Metoda tworząca użytkownika
    private function createUser(): User
    {
        return User::factory()->create();
    }

    // Metoda tworząca rasę psa
    private function createDog(): Dog
    {
        return Dog::create(['breed' => 'Golden Retriever']);
    }

    // Metoda tworząca zwierzę
    private function createAnimal(User $user, Dog $dog): Animal
    {
        return Animal::create([
            'name' => 'Burek',
            'type' => 'dog',
            'breed_id' => $dog->id,
            'age' => 9,
            'description' => 'Przyjazny pies.',
            'country' => 'Polska',
            'city' => 'Rzeszów',
            'c_black' => 1,
            'c_white' => 1,
            'c_other' => 0,
            'owner_id' => $user->id,
        ]);
    }

    public function test_edit_animal_requires_authentication(): void
    {
        $response = $this->get("/animals/1/edit");
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_edit_animal(): void
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $dog = $this->createDog();
        $animal = $this->createAnimal($user, $dog);

        $response = $this->put("/animals/{$animal->id}", [
            'name' => 'Azor',
            'type' => 'dog',
            'breed_id' => $dog->id,
            'age' => 7,
            'description' => 'Edytowany pies z nowym opisem.',
            'country' => 'Niemcy',
            'city' => 'Berlin',
            'c_black' => 0,
            'c_white' => 1,
            'c_other' => 1,
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('animals', [
            'id' => $animal->id,
            'name' => 'Azor',
            'age' => 7,
            'description' => 'Edytowany pies z nowym opisem.',
            'country' => 'Niemcy',
            'city' => 'Berlin',
            'c_black' => 0,
            'c_white' => 1,
            'c_other' => 1,
        ]);
    }

    public function test_authenticated_user_cannot_edit_animal_with_invalid_data(): void
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $dog = $this->createDog();
        $animal = $this->createAnimal($user, $dog);

        $response = $this->put("/animals/{$animal->id}", [
            'name' => '',
            'age' => -1,
            'description' => 'Edytowany pies z nowym opisem.',
            'country' => 'Niemcy',
            'city' => 'Berlin',
        ]);

        $response->assertSessionHasErrors(['name', 'age']);

        $this->assertDatabaseHas('animals', [
            'id' => $animal->id,
            'name' => 'Burek',
            'age' => 9,
        ]);
    }
}
