<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test10_AddAnimalMenuCest
{
    public function testMenuOnAddAnimalPage(AcceptanceTester $I): void
    {
        $I->wantTo('Verify the menu on the Add Animal page');

        $I->amOnPage('/login');
        $I->fillField('email', 'john.doe@gmail.com');
        $I->fillField('password', 'secret');
        $I->click('Log in');


        $I->wait(5);
        $I->seeCurrentUrlEquals('/dashboard');

        $I->click('Dodaj Zwierzaka');
        $I->seeCurrentUrlEquals('/add-animal');

        $I->seeElement('img[alt="Logo "]'); // Logo

        $I->seeLink('Dodaj Zwierzaka', '/add-animal');
        $I->seeLink('Wyszukaj', '/search');
        $I->see('John Doe', 'button');
        $I->click('John Doe');
        $I->waitForText('Konto', 80);
        $I->see('Konto');
        $I->see('Wyloguj');
    }
}
