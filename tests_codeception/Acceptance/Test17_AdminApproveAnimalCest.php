<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;
use PHPUnit\Framework\Assert;

class Test17_AdminApproveAnimalCest
{
    public function testAdminCanApproveAllAnimals(AcceptanceTester $I): void
    {
        // Krok 1: Logowanie jako admin
        $I->wantTo('Log in as admin and approve all animals.');
        $I->amOnPage('/login');
        $I->fillField('email', 'adminStrony@gmail.com');
        $I->fillField('password', 'admin123');
        $I->click('Log in');
        $I->wait(3);

        // Krok 2: Przejście na stronę panelu admina
        $I->seeCurrentUrlEquals('/admin/animals'); // Upewnij się, że przekierowano na panel admina
        $I->see('Lista Zwierząt', 'h2');

        // Krok 3: Pobranie listy zwierząt oczekujących na zatwierdzenie
        $animalsToApprove = $I->grabColumnFromDatabase('animals', 'id', ['isApproved' => 'waiting']);
        codecept_debug("Animals to approve: " . implode(', ', $animalsToApprove));

        // Sprawdzenie, czy są zwierzęta do zatwierdzenia
        $I->see('Zatwierdź zwierzaka', 'button'); // Sprawdź, czy przycisk "Zatwierdź zwierzaka" jest widoczny

        // Krok 4: Iteracja po zwierzętach i zatwierdzanie ich
        foreach ($animalsToApprove as $animalId) {
            // Pobierz nazwę zwierzaka na podstawie ID
            $animalName = $I->grabFromDatabase('animals', 'name', ['id' => $animalId]);
            codecept_debug("Processing animal ID: $animalId, Name: $animalName");

            // Kliknij przycisk "Zatwierdź zwierzaka" dla pierwszego dostępnego zwierzaka
            $I->click('Zatwierdź zwierzaka');
            $I->wait(3);

            // Weryfikacja, czy zwierzę zostało zatwierdzone
            $I->seeInDatabase('animals', [
                'id' => $animalId,
                'isApproved' => 'yes',
            ]);

            // Sprawdzenie komunikatu potwierdzającego
            $I->see('Zwierzę zostało zaakceptowane.');
        }

        $remainingAnimals = $I->grabColumnFromDatabase('animals', 'id', ['isApproved' => 'waiting']);

        if (!empty($remainingAnimals)) {
            codecept_debug("Pozostałe zwierzęta do zatwierdzenia: " . implode(', ', $remainingAnimals));
            Assert::fail("Nie wszystkie zwierzęta zostały zatwierdzone.");
        } else {
            codecept_debug("Wszystkie zwierzęta zostały zatwierdzone.");
        }

    }
}
