<?php

declare(strict_types=1);

namespace TestsCodeception\Support;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * Define custom actions here
     */

    public function logIn(): void
    {
        $this->seeCurrentUrlEquals('/login');
        $this->fillField('email', 'john.doe@gmail.com');
        $this->fillField('password', 'secret');
        $this->waitForNextPage(fn () => $this->click('Log in'));
    }

    public function waitForNextPage(callable $action): void
    {
        // @phpstan-ignore function.alreadyNarrowedType
        if (method_exists($this, 'waitForJS')) {
            $this->waitForJS('return document.oldPage = "yes"');
        }

        $action();

        // @phpstan-ignore function.alreadyNarrowedType
        if (method_exists($this, 'waitForJS')) {
            $this->waitForJS('return document.oldPage !== "yes"');
        }
    }


    /**
     * @param array{
     *     name: string,
     *     type: string,
     *     breed_id: int,
     *     age: int,
     *     description: string,
     *     country: string,
     *     city: string,
     *     colors: string[]
     * } $data
     */
    public function fillAnimalForm(array $data): void
    {
        $I = $this;

        $I->fillField('Imię Zwierzaka', $data['name']);
        $I->selectOption('Typ Zwierzaka', $data['type']);
        $I->selectOption('#breed_id_' . strtolower($data['type']), (string)$data['breed_id']);
        $I->fillField('Wiek', $data['age']);
        $I->fillField('Opis', $data['description']);
        $I->fillField('Kraj', $data['country']);
        $I->fillField('Miasto', $data['city']);

        foreach ($data['colors'] as $color) {
            $I->checkOption('#' . $color);
        }
    }

    /**
     * Pobierz ID rasy z tabeli w bazie danych.
     *
     * @param string $table Nazwa tabeli (np. 'dogs', 'cats')
     * @param string $breed Nazwa rasy
     * @return int ID rasy
     * @throws \RuntimeException Jeśli rasa nie zostanie znaleziona
     */
    public function getBreedId(string $table, string $breed): int
    {
        $this->amOnPage('/dashboard');
        $this->logIn();
        $this->seeInCurrentUrl('/dashboard');

        $breedId = $this->grabFromDatabase($table, 'id', ['breed' => $breed]);
        if (!$breedId) {
            throw new \RuntimeException("Breed ID for '$breed' not found in table '$table'.");
        }

        return $breedId;
    }
}
