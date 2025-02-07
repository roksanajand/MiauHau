<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ustaw dane wstępne do testów.
     */
    protected function setUpTestData(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
    }

    /**
     * Utwórz zalogowanego użytkownika.
     */
    protected function logInUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    /**
     * Zmień status zwierzaka na "yes".
     */
    protected function updateAnimalStatus(string $name, string $status = 'yes'): void
    {
        \DB::table('animals')->where('name', $name)->update(['isApproved' => $status]);
        $this->assertDatabaseHas('animals', [
            'name' => $name,
            'isApproved' => $status,
        ]);
    }

    public function testIndexFiltersAnimalsByTypeAndApproval(): void
    {
        $this->setUpTestData();
        $this->logInUser();

        $response = $this->get(route('search', ['type' => 'dog']));
        $response->assertStatus(200);
        $response->assertViewHas('animals', function ($animals) {
            return $animals->contains(function ($animal) {
                return $animal->name === 'Shadow';
            });
        });

        $response2 = $this->get(route('search', [
            'type' => 'dog',
            'age' => 5,
            'city' => 'Warszawa',
        ]));
        $response2->assertStatus(200);
        $response2->assertViewHas('animals', function ($animals) {
            return $animals->contains(function ($animal) {
                return $animal->name === 'Shadow'
                    && $animal->type === 'dog'
                    && $animal->age == 5
                    && strtolower($animal->city) === 'warszawa';
            });
        });
    }

    public function testIndexFiltersAnimalsByTypeCat(): void
    {
        $this->setUpTestData();
        $this->updateAnimalStatus('Kitty');
        $this->logInUser();

        $response = $this->get(route('search', ['type' => 'cat']));
        $response->assertStatus(200);
        $response->assertViewHas('animals', function ($animals) {
            return $animals->contains(function ($animal) {
                return $animal->name === 'Kitty'
                    && $animal->type === 'cat'
                    && $animal->isApproved === 'yes';
            });
        });
    }

    public function testIndexFiltersAnimalsByColor(): void
    {
        $this->setUpTestData();
        $this->updateAnimalStatus('Kitty');
        $this->logInUser();

        $response = $this->get(route('search', [
            'type' => 'cat',
            'c_white' => 1,
            'c_brown' => 1,
        ]));
        $response->assertStatus(200);
        $response->assertViewHas('animals', function ($animals) {
            return $animals->contains(function ($animal) {
                return $animal->name === 'Kitty'
                    && $animal->type === 'cat'
                    && $animal->c_white == 1
                    && $animal->c_brown == 1
                    && $animal->isApproved === 'yes';
            });
        });
    }

    public function testIndexFiltersAnimalsByWhiteColor(): void
    {
        $this->setUpTestData();
        $this->updateAnimalStatus('Kitty');
        $this->logInUser();

        $response = $this->get(route('search', ['c_white' => 1]));
        $response->assertStatus(200);
        $response->assertViewHas('animals', function ($animals) {
            return $animals->contains(function ($animal) {
                return $animal->name === 'Kitty'
                    && $animal->c_white == 1
                    && $animal->isApproved === 'yes';
            }) && $animals->contains(function ($animal) {
                return $animal->name === 'Shadow'
                    && $animal->c_white == 1
                    && $animal->isApproved === 'yes';
            });
        });
    }

    public function testIndexFiltersDogsAfterChangingBuddyStatus(): void
    {
        $this->setUpTestData();
        $this->updateAnimalStatus('Buddy');
        $this->logInUser();

        $response = $this->get(route('search', ['type' => 'dog']));
        $response->assertStatus(200);
        $response->assertViewHas('animals', function ($animals) {
            return $animals->contains(function ($animal) {
                return $animal->name === 'Buddy'
                    && $animal->type === 'dog'
                    && $animal->isApproved === 'yes';
            }) && $animals->contains(function ($animal) {
                return $animal->name === 'Shadow'
                    && $animal->type === 'dog'
                    && $animal->isApproved === 'yes';
            });
        });
    }

    public function testIndexFiltersAnimalsByCityCaseInsensitive(): void
    {
        $this->setUpTestData();
        $this->updateAnimalStatus('Buddy');
        $this->logInUser();

        $response = $this->get(route('search', ['city' => 'NOWY JORK']));
        $response->assertStatus(200);
        $response->assertViewHas('animals', function ($animals) {
            return $animals->count() === 1
                && $animals->first()->name === 'Buddy'
                && strtolower($animals->first()->city) === 'nowy jork';
        });
    }
}
