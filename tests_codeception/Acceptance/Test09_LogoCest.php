<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test09_LogoCest
{
    public function testLogoFunctionalityOnMultiplePages(AcceptanceTester $I): void
    {
        $I->wantTo('create a sample account and verify that the logo is visible and clicking on it redirects to the home page on all pages');

        // Tworzenie przykładowego użytkownika
        $I->amOnPage('/register');
        $I->fillField('name', 'Test User 2');
        $I->fillField('email', 'testuser2@example.com');
        $I->fillField('password', 'password123');
        $I->fillField('password_confirmation', 'password123');
        $I->click('Register');

        // Lista stron do przetestowania
        $pages = [
            '/',
            '/confirm-password',

        ];

        foreach ($pages as $page) {
            // Przejdź do aktualnie testowanej strony
            $I->amOnPage($page);

            $I->wait(6);
            // Sprawdzenie, czy logo jest widoczne
            $I->seeElement('img.logo');

            // Kliknięcie w logo
            $I->click('img.logo');
            $I->wait(3);
            // Sprawdzenie, czy użytkownik został przeniesiony na stronę główną
            $I->seeCurrentUrlEquals('/');
        }
    }
}
