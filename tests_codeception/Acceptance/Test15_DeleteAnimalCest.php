<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test15_DeleteAnimalCest
{
    public function testDeleteAnimal(AcceptanceTester $I): void
    {
        $I->wantTo('verify that the Delete Animal feature works correctly');



        $dogBreed = 'Golden Retriever';



        $dogBreedId = $I->getBreedId('dogs', $dogBreed);



        $I->amOnPage('/add-animal');
        $I->fillAnimalForm([
            'name' => 'Burek',
            'type' => 'dog',
            'breed_id' => $dogBreedId,
            'age' => 9,
            'description' => 'Pies do usunięcia.',
            'country' => 'Polska',
            'city' => 'Rzeszów',
            'colors' => ['c_black', 'c_white'],
        ]);
        $I->click('button[type=submit]');
        $I->waitForText('Pupil został pomyślnie dodany!', 10, 'body');
        $I->seeInCurrentUrl('/dashboard');

        // Pobierz ID dodanego psa z bazy danych
        $dogAnimalId = $I->grabFromDatabase('animals', 'id', [
            'name' => 'Burek',
            'type' => 'dog',
            'breed_id' => $dogBreedId,
            'country' => 'Polska',
            'city' => 'Rzeszów',
            'c_black' => 1,
            'c_white' => 1,
        ]);
        if (!$dogAnimalId) {
            throw new \RuntimeException("Dog record not found in database after submission.");
        }


        $I->amOnPage("/animals/$dogAnimalId/delete");
        $I->see('Usuń Zwierzaka', 'h2');
        $I->see("Czy na pewno chcesz usunąć zwierzaka: Burek?", 'body');


        $I->fillField('#password', 'secret');
        $I->click('button[type=submit]'); // Przycisk "Usuń"


        $I->waitForText('Zwierzak został usunięty.', 10, 'body');
        $I->seeInCurrentUrl('/dashboard');

        // czy rekord został usunięty z bazy danych
        $I->dontSeeInDatabase('animals', [
            'id' => $dogAnimalId,
        ]);
    }
}
