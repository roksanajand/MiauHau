<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test16_LikeAnimalCest
{
    public function testViewAnimalsOnSearchPage(AcceptanceTester $I): void
    {
        // Krok 1: Logowanie
        $I->wantTo('Log in as John Doe and view animals on the search page.');
        $I->amOnPage('/login');
        $I->fillField('email', 'john.doe@gmail.com');
        $I->fillField('password', 'secret');
        $I->click('Log in');
        $I->wait(3);

        // Krok 2: Przejście na stronę wyszukiwania
        $I->seeCurrentUrlEquals('/dashboard'); // Upewnij się, że przekierowano na dashboard
        $I->click('Wyszukaj'); // Kliknięcie w link "Wyszukaj"
        $I->wait(3);
        $loggedInUserId = $I->grabFromDatabase('users', 'id', ['email' => 'john.doe@gmail.com']);

        // Sprawdzenie, czy jesteśmy na stronie wyszukiwania
        $I->seeCurrentUrlEquals('/search');
        $I->see('Wyszukaj Zwierzęta', 'h2');
        $animalName = $I->grabTextFrom('table tbody tr:first-child td:nth-child(2)');

        $I->seeElement('table'); // Sprawdź, czy tabela wyników istnieje

        // Krok 4: Wyszukanie przycisku "Lubię to"
        $I->see('Lubię to', 'button'); // Sprawdzenie, czy przycisk "Lubię to" jest widoczny

        $I->click('Lubię to'); // Kliknięcie w pierwszy widoczny przycisk "Lubię to"
        $I->wait(3);

        $I->see('Usuń polubienie', 'button'); // Sprawdź, czy przycisk zmienił się na "Usuń polubienie"
        $animalId = $I->grabFromDatabase('animals', 'id', ['name' => $animalName]);
        codecept_debug("Animal ID: $animalId");

        $I->seeInDatabase('likes', [
            'animal_id' => $animalId,
            'user_id' => $loggedInUserId,
        ]);
        $I->click('Usuń polubienie');
        $I->wait(3);

        // Sprawdzenie, czy wpis został usunięty z tabeli `likes`
        $I->dontSeeInDatabase('likes', [
            'animal_id' => $animalId,
            'user_id' => $loggedInUserId,
        ]);

    }
}
