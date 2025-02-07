<?php

namespace Tests\Feature\Auth;

use App\Models\Animal;
use App\Models\Dog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteAnimalTest extends TestCase
{
    public function test_delete_animal_requires_authentication(): void
    {
        $response = $this->delete('/animals/1');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_delete_animal(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret_password'),
        ]);

        $this->actingAs($user);

        $dog = Dog::create(['breed' => 'Golden Retriever']);

        $animal = Animal::create([
            'name' => 'Burek',
            'type' => 'dog',
            'breed_id' => $dog->id,
            'age' => 9,
            'description' => 'Pies do usunięcia.',
            'country' => 'Polska',
            'city' => 'Rzeszów',
            'c_black' => 1,
            'c_white' => 1,
            'c_other' => 0,
            'owner_id' => $user->id,
        ]);

        $response = $this->delete("/animals/{$animal->id}", [
            'password' => 'secret_password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseMissing('animals', ['id' => $animal->id]);
    }


    public function test_authenticated_user_cannot_delete_animal_with_incorrect_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct_password'),
        ]);

        $this->actingAs($user);

        $dog = Dog::create(['breed' => 'Golden Retriever']);

        $animal = Animal::create([
            'name' => 'Shadow',
            'type' => 'dog',
            'breed_id' => $dog->id,
            'age' => 5,
            'description' => 'Testowy piesek.',
            'country' => 'Polska',
            'city' => 'Kraków',
            'c_black' => 1,
            'c_white' => 0,
            'c_other' => 0,
            'owner_id' => $user->id,
        ]);

        $response = $this->delete("/animals/{$animal->id}", [
            'password' => 'wrong_password',
        ]);


        $response->assertSessionHasErrors(['password']);


        $this->get("/animals/{$animal->id}/delete")
            ->assertSeeText('Niepoprawne hasło lub brak autoryzacji');


        $this->assertDatabaseHas('animals', ['id' => $animal->id]);
    }



    public function test_deleting_non_existent_animal_returns_404(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct_password'),
        ]);
        $this->actingAs($user);

        $response = $this->delete('/animals/999', [
            'password' => 'correct_password',
        ]);

        $response->assertStatus(404);
    }
}
