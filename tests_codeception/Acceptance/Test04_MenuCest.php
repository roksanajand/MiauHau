<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test04_MenuCest
{
    public function testMenu(AcceptanceTester $I): void
    {
        $I->wantTo('verify that the menu contains all required elements');

        // Przejdź do strony głównej (lub innej po zalogowaniu)
        $I->amOnPage('/dashboard');
        $I->logIn();

        $I->seeCurrentUrlEquals('/dashboard');


        // Sprawdź, czy widoczne są linki do "Dodaj Zwierzaka" i "Wyszukaj"
        $I->seeLink('Moje Zwierzaki', '/dashboard');
        $I->seeLink('Dodaj Zwierzaka', '/add-animal');
        $I->seeLink('Wyszukaj', '/search');
        $I->see('John Doe', 'button');
        $I->click('John Doe');
        $I->waitForText('Konto', 80);
        $I->see('Konto');
        $I->see('Wyloguj');

    }
}
