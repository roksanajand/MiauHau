<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test12_ProfilePictureCest
{
    public function testAnimalPhotosForDora(AcceptanceTester $I): void
    {
        // Logowanie na konto użytkownika
        $I->wantTo('Check if the animal photos displayed are correct for animals on Dora Smith account');
        $I->amOnPage('/login');
        $I->fillField('email', 'dora.smith@gmail.com');
        $I->fillField('password', 'dora123');
        $I->click('Log in');

        // oczekiwanie na pojawienie się
        $I->wait(5);


        $I->seeCurrentUrlEquals('/dashboard');

        // Pobieranie ID użytkownika na podstawie adresu e-mail
        $userId = $I->grabFromDatabase('users', 'id', ['email' => 'dora.smith@gmail.com']);
        if (!$userId) {
            throw new \RuntimeException("Nie znaleziono użytkownika o adresie email dora.smith@gmail.com");
        }

        // Pobieranie danych zwierząt użytkownika
        $animalPhotos = $I->grabColumnFromDatabase('animals', 'photo', ['owner_id' => $userId]);

        // Jeśli użytkownik nie ma zwierząt
        if (empty($animalPhotos)) {
            $I->see('Nie masz jeszcze dodanych zwierząt.');
            return;
        }


        foreach ($animalPhotos as $photoPath) {
            // Generowanie pełnej ścieżki URL do zdjęcia
            $fullImagePath = 'http://localhost:8888/' . $photoPath; // Pełna ścieżka URL

            $I->seeElement('img[src="' . $fullImagePath . '"]');
        }
    }
}
