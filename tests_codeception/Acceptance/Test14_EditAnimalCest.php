<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test14_EditAnimalCest
{
    public function testEditAnimal(AcceptanceTester $I): void
    {
        $I->wantTo('verify that the Edit Animal form works correctly');
        $I->resizeWindow(1920, 1080); // nie jest robiona sresponsywność zatem wymuszenie dużego okna do testów




        $dogBreed = 'Golden Retriever';

        $dogBreedId = $I->getBreedId('dogs', $dogBreed);



        // Dodaj psa
        $I->amOnPage('/add-animal');
        $I->fillAnimalForm([
            'name' => 'Reks',
            'type' => 'dog', // lub 'cat'
            'breed_id' => $dogBreedId,

            'age' => 8,
            'description' => 'Przyjazny piesek.',
            'country' => 'Polska',
            'city' => 'Kraków',
            'colors' => ['c_black', 'c_white', 'c_other'],
        ]);


        $I->click('button[type=submit]');


        // Pobierz ID dodanego psa z bazy danych
        $dogAnimalId = $I->grabFromDatabase('animals', 'id', [
            'name' => 'Reks',
            'type' => 'dog',
            'breed_id' => $dogBreedId,
            'country' => 'Polska',
            'city' => 'Kraków',
            'c_black' => 1,
            'c_white' => 1,
            'c_other' => 1,
        ]);
        if (!$dogAnimalId) {
            throw new \RuntimeException("Dog record not found in database after submission.");
        }

        $I->amOnPage("/animals/$dogAnimalId/edit");

        // Wypełnij nowe dane w formularzu edycji
        $I->fillField('Imię', 'Azor');
        $I->selectOption('#type', 'Pies');
        $I->selectOption('#breed_id_dog', (string)$dogBreedId);
        $I->fillField('Wiek', '7');
        $I->fillField('Opis', 'Edytowany pies z nowym opisem.');
        $I->fillField('Kraj', 'Niemcy');
        $I->fillField('Miasto', 'Berlin');

        // Zmień kolory
        $I->uncheckOption('#c_black');
        $I->uncheckOption('#c_white');
        $I->uncheckOption('#c_other');
        $I->checkOption('#c_golden');
        $I->checkOption('#c_tricolor');

        // "Zapisz zmiany"
        $I->click('button[type=submit]');

        //przekierowanie na /dashboard i komunikat o sukcesie
        $I->wait(5);
        $I->seeInCurrentUrl('/dashboard');
        $I->waitForText('Zwierzak został zaktualizowany.', 10, 'body');




        // Weryfikuj w bazie danych nowe wartości
        $I->seeInDatabase('animals', [
            'id' => $dogAnimalId,
            'name' => 'Azor',
            'type' => 'dog',
            'breed_id' => $dogBreedId,
            'age' => 7,
            'description' => 'Edytowany pies z nowym opisem.',
            'country' => 'Niemcy',
            'city' => 'Berlin',
            'c_white' => 0,
            'c_other' => 0,
            'c_golden' => 1,
            'c_tricolor' => 1,
            'isApproved' => 'waiting',
        ]);


        $I->see('Azor');
        $I->see('7'); // Wiek psa
        $I->see('Golden Retriever');
        $I->see('Niemcy');
        $I->see('Berlin');
        $I->see('Trójkolorowy, Złoty');
        $I->see('Czeka na akceptację');

        $I->click('John Doe');
        $I->click('Wyloguj');

        $I->wait(3);

        $I->amOnPage('/login');
        $I->fillField('email', 'adminStrony@gmail.com');
        $I->fillField('password', 'admin123');
        $I->click('Log in');

        $I->see('Zobacz zmiany');
        $I->click('Zobacz zmiany');
        $I->amOnPage("admin/animals/$dogAnimalId/history");


        $I->see('Informacje przed zmianą');
        $I->see('Reks');
        $I->see('8'); // Wiek psa
        $I->see('Golden Retriever');
        $I->see('biały, czarny, inny');
        $I->see('Polska');
        $I->see('Kraków');
        $I->see('Przyjazny piesek.');










    }
}
