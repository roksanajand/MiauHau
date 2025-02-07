<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test06_PhotoCarouselCest
{
    private function logInAndApproveAnimals(AcceptanceTester $I): void
    {
        // Logowanie jako administrator
        $I->amOnPage('/login');
        $I->wait(10);
        $I->fillField('email', 'adminStrony@gmail.com');
        $I->fillField('password', 'admin123');
        $I->click('Log in');

        // Akceptacja zwierząt
        $I->wait(2);
        $I->click('Zatwierdź zwierzaka');
        $I->wait(2);
        $I->see('Zwierzę zostało zaakceptowane.');
        $I->click('Zatwierdź zwierzaka');
        $I->wait(2);
        $I->see('Zwierzę zostało zaakceptowane.');
        $I->see('Wyloguj');
        $I->click('Wyloguj');
    }
    public function testPhotoCarouselArrowsForLoggedInUser(AcceptanceTester $I): void
    {
        $I->wantTo('Verify the Admin page');

        $this->logInAndApproveAnimals($I);

        $I->wantTo('Verify the photo carousel arrows function correctly for logged-in users.');

        // Ponowne logowanie
        $I->amOnPage('/login');
        $I->fillField('email', 'Dora.smith@gmail.com');
        $I->fillField('password', 'dora123');
        $I->click('Log in');
        $I->wait(2);
        $I->see('Moje Zwierzaki'); // Potwierdzenie logowania

        // Przejście na stronę główną
        $I->amOnPage('/');
        $I->wait(2); // Czekanie na załadowanie strony

        // Sprawdzenie obecności karuzeli zdjęć
        $I->seeElement('.image-wrapper');
        $initialPhotos = $I->grabMultiple('.image-wrapper .image-item img', 'src');

        // Sprawdzenie informacji dla pierwszego zdjęcia w karuzeli
        $I->see('Imię:');
        $I->see('Miasto:');
        $I->see('Kontakt do właściciela:');

        // Sprawdzenie czy można kliknąć w Lubie to
        $I->click('.like-section button');
        $I->wait(2); // Wait for AJAX response
        $I->seeElement('.like-section button'); // Confirm button still exists post-click

        // Pobranie pozycji pierwszego obrazka przed i po kliknięciu
        $positionBefore = $I->executeJS("return document.querySelector('.image-wrapper .image-item img').getBoundingClientRect().left;");
        $I->click('.arrow.right');
        $I->wait(2);
        $positionAfter = $I->executeJS("return document.querySelector('.image-wrapper .image-item img').getBoundingClientRect().left;");

        // Sprawdzenie, czy pozycja obrazka się zmieniła
        \PHPUnit\Framework\Assert::assertNotEquals($positionBefore, $positionAfter, 'Pozycja obrazów powinna się zmienić po kliknięciu w strzałkę w prawo.');

        // Wylogowanie użytkownika
        $I->click('Panel Użytkownika');
        $I->wait(2);
        $I->see('Dorothéa Smith', 'button');
        $I->click('Dorothéa Smith');
        $I->wait(2);
        $I->waitForText('Konto', 80);
        $I->see('Wyloguj');
        $I->wait(2);
        $I->click('Wyloguj');
        $I->wait(2);
        $I->see('Zaloguj się');
    }

    public function testPhotoCarouselForGuestUser(AcceptanceTester $I): void
    {
        $I->wantTo('Zweryfikować stronę administratora');

        $this->logInAndApproveAnimals($I);

        $I->wantTo('Zweryfikować, czy karuzela zdjęć wyświetla poprawne zdjęcia dla niezalogowanych użytkowników bez zdjęcia lapka.jpg.');

        // Przejście na stronę główną
        $I->amOnPage('/');
        $I->wait(2); // Oczekiwanie na załadowanie strony

        // Sprawdzenie widoczności karuzeli i zdjęć
        $I->seeElement('.image-wrapper');
        $photosFromPage = $I->grabMultiple('.image-wrapper .image-item img', 'src');

        // Sprawdzenie, czy tablica zdjęć nie jest pusta
        \PHPUnit\Framework\Assert::assertNotEmpty($photosFromPage, 'Zdjęcia powinny być wyświetlane dla niezalogowanego użytkownika.');

        // Sprawdzenie, czy wyświetlane zdjęcia są zgodne z oczekiwaniami
        $allPhotos = $I->grabColumnFromDatabase('animals', 'photo', ['isApproved' => 'yes']);
        $filteredPhotos = array_filter($allPhotos, fn ($photo) => stripos($photo, 'lapka.jpg') === false);

        foreach ($filteredPhotos as $photoPath) {
            $fullPath = 'http://localhost:8888/' . $photoPath;
            $I->seeElement('img[src="' . $fullPath . '"]');
        }

        // Sprawdzenie, czy sekcja polubień nie jest widoczna dla gości
        $I->dontSeeElement('.like-section');

        // Pobranie pozycji pierwszego obrazka przed i po kliknięciu
        $positionBefore = $I->executeJS("return document.querySelector('.image-wrapper .image-item img').getBoundingClientRect().left;");
        $I->click('.arrow.right');
        $I->wait(2);
        $positionAfter = $I->executeJS("return document.querySelector('.image-wrapper .image-item img').getBoundingClientRect().left;");

        // Sprawdzenie, czy pozycja obrazka się zmieniła
        \PHPUnit\Framework\Assert::assertNotEquals($positionBefore, $positionAfter, 'Pozycja obrazów powinna się zmienić po kliknięciu w strzałkę w prawo.');
    }

}
