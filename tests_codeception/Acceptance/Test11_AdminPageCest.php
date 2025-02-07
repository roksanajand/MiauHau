<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;
use PHPUnit\Framework\Assert;

class Test11_AdminPageCest
{
    public function testMenuOnAddAnimalPage(AcceptanceTester $I): void
    {
        $I->wantTo('Verify the Admin page');

        // Logowanie jako admin
        $I->amOnPage('/login');
        $I->fillField('email', 'adminStrony@gmail.com');
        $I->fillField('password', 'admin123');
        $I->click('Log in');

        $I->wait(5);
        $I->seeCurrentUrlEquals('/admin/animals');

        // Sprawdzenie elementów na stronie
        $I->see('Wyloguj');
        $I->see('Zdjęcie', 'th');
        $I->see('Imię', 'th');
        $I->see('Typ', 'th');
        $I->see('Opis', 'th');
        $I->see('Status', 'th');
        $I->see('Typ operacji', 'th');
        $I->see('Historia zmian', 'th');
        $I->see('Akcje', 'th');

        // Pobranie ID zwierząt z bazy danych, które oczekują na zatwierdzenie
        $animalsToApprove = $I->grabColumnFromDatabase('animals', 'id', ['isApproved' => 'waiting']);
        codecept_debug("Animals to approve from database: " . implode(', ', $animalsToApprove));

        // Pobranie nazw zwierząt na podstawie ID
        $animalNamesFromDatabase = [];
        foreach ($animalsToApprove as $animalId) {
            $animalName = $I->grabFromDatabase('animals', 'name', ['id' => $animalId]);
            $animalNamesFromDatabase[] = $animalName;
        }
        codecept_debug("Animal names from database: " . implode(', ', $animalNamesFromDatabase));

        // Pobranie nazw zwierząt z tabeli na stronie
        $animalNamesFromPage = $I->grabMultiple('table tbody tr td:nth-child(2)', 'text');
        codecept_debug("Animal names from page: " . implode(', ', $animalNamesFromPage));

        // Sprawdzenie, czy liczba zwierząt w bazie i na stronie jest zgodna
        Assert::assertEquals(
            count($animalNamesFromDatabase),
            count($animalNamesFromPage),
            'Liczba zwierząt na stronie nie zgadza się z liczbą w bazie danych.'
        );

    }
}
