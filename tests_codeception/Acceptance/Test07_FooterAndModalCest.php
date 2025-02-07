<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test07_FooterAndModalCest
{
    public function testFooterAndModalOnMultiplePages(AcceptanceTester $I): void
    {
        $I->wantTo('verify that the footer and modal functionality work correctly on all pages with existing admin data');

        // Lista stron do przetestowania
        $pages = [
            '/', // Strona główna
            '/confirm-password',
            '/forgot-password',
            '/login',
            '/register',
            '/verify-email',
            '/dashboard',
            '/add-animal',
            '/profile',
        ];

        foreach ($pages as $page) {
            // Przejdź do aktualnie testowanej strony
            $I->amOnPage($page);

            // Sprawdzenie, czy widoczne są elementy w stopce
            $I->see('Kontakt', 'a#contactLink');
            $I->see('Regulamin', 'a[href*="Regulamin.pdf"]');
            $I->see('Kraków 2025', '.footer-subtext');

            // Kliknięcie na "Kontakt"
            $I->click('#contactLink');

            // Sprawdzenie widoczności modala
            $I->see('Administrator: Admin', '.modal-content');
            $I->see('Email: adminStrony@gmail.com', '.modal-content');

            // Zamknięcie modala
            $I->click('.close');
            $I->dontSeeElement('.contact-modal[style*="display: flex"]');
        }
    }
}
