<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class UserPageAnimalsCest
{
    // Funkcja pomocnicza do przetwarzania kolorów zwierząt
    private function getAnimalColors(AcceptanceTester $I, int $userId, int $index): string
    {
        $colors = [];
        $black = $I->grabColumnFromDatabase('animals', 'c_black', ['owner_id' => $userId]);
        $white = $I->grabColumnFromDatabase('animals', 'c_white', ['owner_id' => $userId]);
        $ginger = $I->grabColumnFromDatabase('animals', 'c_ginger', ['owner_id' => $userId]);
        $tricolor = $I->grabColumnFromDatabase('animals', 'c_tricolor', ['owner_id' => $userId]);
        $grey = $I->grabColumnFromDatabase('animals', 'c_grey', ['owner_id' => $userId]);
        $brown = $I->grabColumnFromDatabase('animals', 'c_brown', ['owner_id' => $userId]);
        $golden = $I->grabColumnFromDatabase('animals', 'c_golden', ['owner_id' => $userId]);
        $other = $I->grabColumnFromDatabase('animals', 'c_other', ['owner_id' => $userId]);

        // Dodanie kolorów do tablicy
        if ($black[$index] == 1) {
            $colors[] = 'Czarny';
        }
        if ($white[$index] == 1) {
            $colors[] = 'Biały';
        }
        if ($ginger[$index] == 1) {
            $colors[] = 'Rudy';
        }
        if ($tricolor[$index] == 1) {
            $colors[] = 'Trójkolorowy';
        }
        if ($grey[$index] == 1) {
            $colors[] = 'Szary';
        }
        if ($brown[$index] == 1) {
            $colors[] = 'Brązowy';
        }
        if ($golden[$index] == 1) {
            $colors[] = 'Złoty';
        }
        if ($other[$index] == 1) {
            $colors[] = 'Inny';
        }

        return implode(', ', $colors); // Zwrócenie połączonych kolorów
    }

    /**
     * @param array<string> $animalData
     * @return void
     */
    private function checkAnimalData(AcceptanceTester $I, array $animalData): void
    {
        // Iterowanie przez dane zwierzęcia i sprawdzanie, czy tekst jest widoczny
        foreach ($animalData as $key => $value) {
            $I->waitForText($value, 10);
            $I->see($value);
        }
    }

    private function logInAndCheckAnimals(AcceptanceTester $I, string $email, string $password): void
    {
        $I->wantTo("verify that user with email $email sees their animals on the dashboard");

        // Logowanie
        $I->amOnPage('/');
        $I->click('Zaloguj się');
        $I->seeInCurrentUrl('/login');
        $I->fillField('#email', $email);
        $I->fillField('#password', $password);
        $I->click('Log in');

        // Pobieranie użytkownika
        $userId = $I->grabFromDatabase('users', 'id', ['email' => $email]);
        if (!$userId) {
            throw new \RuntimeException("Nie znaleziono użytkownika o adresie email $email");
        }

        // Pobieranie danych dla zwierząt użytkownika
        $animalNames = $I->grabColumnFromDatabase('animals', 'name', ['owner_id' => $userId]);
        $animalTypes = $I->grabColumnFromDatabase('animals', 'type', ['owner_id' => $userId]);
        $animalBreedIds = $I->grabColumnFromDatabase('animals', 'breed_id', ['owner_id' => $userId]);
        $animalAges = $I->grabColumnFromDatabase('animals', 'age', ['owner_id' => $userId]);
        $animalDescriptions = $I->grabColumnFromDatabase('animals', 'description', ['owner_id' => $userId]);
        $animalCountries = $I->grabColumnFromDatabase('animals', 'country', ['owner_id' => $userId]);
        $animalCities = $I->grabColumnFromDatabase('animals', 'city', ['owner_id' => $userId]);

        foreach ($animalNames as $index => $name) {
            // Przetwarzanie kolorów za pomocą funkcji pomocniczej
            $colors = $this->getAnimalColors($I, $userId, $index);

            // Pobieranie rasy zwierzęcia
            $breed = '';
            if ($animalTypes[$index] === 'cat') {
                $breed = $I->grabFromDatabase('cats', 'breed', ['id' => $animalBreedIds[$index]]) ?? '';
            } elseif ($animalTypes[$index] === 'dog') {
                $breed = $I->grabFromDatabase('dogs', 'breed', ['id' => $animalBreedIds[$index]]) ?? '';
            }

            // Funkcja do rzutowania na łańcuch znakowy
            $safeStrval = function ($value) {
                if (is_array($value) || is_object($value)) {
                    return '';
                }
                return (string)($value ?? '');
            };

            // Przygotowanie wartości do przekazania jako stringi
            $name = $safeStrval($name);
            $type = $safeStrval($animalTypes[$index]);
            $age = $safeStrval($animalAges[$index] ?? 0);
            $description = $safeStrval($animalDescriptions[$index]);
            $country = $safeStrval($animalCountries[$index]);
            $city = $safeStrval($animalCities[$index]);

            // Przygotowanie danych zwierzęcia do sprawdzenia
            $animalData = [
                'name' => $name,
                'type' => $type === 'dog' ? 'pies' : ($type === 'cat' ? 'kot' : $type),
                'breed' => $breed,
                'age' => $age,
                'description' => $description,
                'country' => $country,
                'city' => $city,
                'colors' => $colors,
            ];

            $this->checkAnimalData($I, $animalData);
        }
    }

    public function checkUserAnimalsForDora(AcceptanceTester $I): void
    {
        $this->logInAndCheckAnimals($I, 'dora.smith@gmail.com', 'dora123');
    }

    public function checkUserAnimalsForMartin(AcceptanceTester $I): void
    {
        $this->logInAndCheckAnimals($I, 'martin.smithson@gmail.com', '123');
    }
}
