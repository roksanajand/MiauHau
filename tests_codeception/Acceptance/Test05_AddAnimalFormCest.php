<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test05_AddAnimalFormCest
{
    public function testAddAnimalForm(AcceptanceTester $I): void
    {
        $I->resizeWindow(1920, 1080);
        $I->wantTo('verify that the Add Animal form works correctly');

        $dogBreed = 'Golden Retriever';
        $catBreed = 'Norweski leśny';

        /** @var int|null $dogBreedId */
        $dogBreedId = $I->grabFromDatabase('dogs', 'id', ['breed' => $dogBreed]);
        if (!$dogBreedId) {
            throw new \RuntimeException("Dog breed ID for '$dogBreed' not found in database.");
        }

        /** @var int|null $catBreedId */
        $catBreedId = $I->grabFromDatabase('cats', 'id', ['breed' => $catBreed]);
        if (!$catBreedId) {
            throw new \RuntimeException("Cat breed ID for '$catBreed' not found in database.");
        }


        $I->amOnPage('/dashboard');
        $I->logIn();
        $I->seeInCurrentUrl('/dashboard');

        // Dodaj psa
        $I->amOnPage('/add-animal');
        $I->fillAnimalForm([
            'name' => 'Burek',
            'type' => 'dog',
            'breed_id' => $dogBreedId,
            'age' => 5,
            'description' => 'Przyjazny pies z wieloma kolorami.',
            'country' => 'Polska',
            'city' => 'Warszawa',
            'colors' => ['c_black', 'c_white', 'c_other'],
        ]);



        $I->click('button[type=submit]');

        // przekierowanie na dashboard i obecność komunikatu

        $I->waitForText('Pupil został pomyślnie dodany!', 10, 'body');

        $I->seeInCurrentUrl('/dashboard');


        // Pobierz ID dodanego psa z bazy danych
        $dogAnimalId = $I->grabFromDatabase('animals', 'id', [
            'name' => 'Burek',
            'type' => 'dog',
            'breed_id' => $dogBreedId,
            'country' => 'Polska',
            'city' => 'Warszawa',
            'c_black' => 1,
            'c_white' => 1,
            'c_other' => 1,
        ]);
        if (!$dogAnimalId) {
            throw new \RuntimeException("Dog record not found in database after submission.");
        }

        // Dodaj kota
        $I->amOnPage('/add-animal');
        $I->fillAnimalForm([
            'name' => 'Mruczek',
            'type' => 'cat', // Typ zwierzaka: kot
            'breed_id' => $catBreedId,
            'age' => 3,
            'description' => 'Milutki kot.',
            'country' => 'Polska',
            'city' => 'Kraków',
            'colors' => ['c_grey', 'c_brown'], // Kolory zaznaczone w formularzu
        ]);

        $I->click('button[type=submit]');




        $I->waitForText('Pupil został pomyślnie dodany!', 10, 'body');
        $I->seeInCurrentUrl('/dashboard');


        // Pobierz ID dodanego kota z bazy danych
        $catAnimalId = $I->grabFromDatabase('animals', 'id', [
            'name' => 'Mruczek',
            'type' => 'cat',
            'breed_id' => $catBreedId,
            'country' => 'Polska',
            'city' => 'Kraków',
            'c_grey' => 1,
            'c_brown' => 1,
        ]);
        if (!$catAnimalId) {
            throw new \RuntimeException("Cat record not found in database after submission.");
        }

        $I->haveInDatabase('likes', ['user_id' => 1, 'animal_id' => $dogAnimalId]);
        $I->haveInDatabase('likes', ['user_id' => 1, 'animal_id' => $catAnimalId]);

        // Sprawdź, czy dane są widoczne na dashboardzie
        $I->amOnPage('/dashboard');
        $I->see('Burek');
        $I->see('5'); // Wiek psa
        $I->see('Golden Retriever');
        $I->see('Czarny, Biały, Inny');
        $I->see('Polska');
        $I->see('Warszawa');
        $I->waitForElementVisible("//td[contains(text(),'1')]", 5);
        $I->see("1");



        $I->see('Mruczek');
        $I->see('3'); // Wiek kota
        $I->see('Norweski leśny');
        $I->see('Szary, Brązowy');
        $I->see('Polska');
        $I->see('Kraków');
        $I->waitForElementVisible("//td[contains(text(),'1')]", 5);
        $I->see('1'); // Polubienia kota



    }

}
