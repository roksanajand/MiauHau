<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;
use PHPUnit\Framework\Assert;

class Test13_SearchAnimalCest
{
    public function testMenuOnSearchPage(AcceptanceTester $I): void
    {
        $I->wantTo('Verify the menu and functionality on the Search Animals page');

        $I->amOnPage('/login');
        $I->fillField('email', 'adminStrony@gmail.com');
        $I->fillField('password', 'admin123');
        $I->click('Log in');
        $I->wait(5);

        // Przekierowanie na panel admina
        $I->seeInCurrentUrl('/admin/animals');
        $I->see('Lista Zwierząt', 'h2');

        // Pobranie wszystkich zwierząt o statusie "waiting"
        $animalsToApprove = $I->grabColumnFromDatabase('animals', 'id', [
            'isApproved' => 'waiting'
        ]);

        // Akceptacja każdego zwierzaka
        foreach ($animalsToApprove as $animalId) {
            $I->updateInDatabase('animals', ['isApproved' => 'yes'], ['id' => $animalId]);
        }
        // Wylogowanie admina
        $I->click('Wyloguj');

        $I->wait(5);
        $I->amOnPage('/login');

        $I->fillField('email', 'martin.smithson@gmail.com');
        $I->fillField('password', '123');
        $I->wait(5);
        $I->click('Log in');

        // Sprawdzenie przekierowania na Dashboard
        $I->wait(5);
        $I->seeCurrentUrlEquals('/dashboard');
        $I->click('Wyszukaj');
        $I->wait(5);
        $I->seeCurrentUrlEquals('/search');
        // Przejście do podstrony Wyszukaj

        // Sprawdzenie formularza wyszukiwania
        $I->see('Wyszukaj Zwierzęta', 'h2');
        $I->see('Typ Zwierzęcia', 'label');
        $I->see('Wiek (lata)', 'label');
        $I->see('Miasto', 'label');
        $I->see('Kolor', 'label');
        $I->see('Wyszukaj', 'button');
        $I->fillField('#age', '2');
        $I->wait(1); // Czekamy chwilę na wpisanie wartości
        $I->seeInField('#age', '2'); // Sprawdzamy, czy pole zawiera wartość
        $I->seeElement('button[type=submit]'); // Sprawdzamy obecność przycisku
        $I->click('button[type=submit]'); // Klikamy w przycisk
        $I->wait(5);

        $I->scrollTo('table'); // Przewija stronę do tabeli wyników
        $I->waitForElementVisible('table', 10); // Czekamy na widoczność tabeli

    }
}
