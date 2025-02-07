<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test00_HomepageCest
{
    public function test(AcceptanceTester $I): void
    {
        $I->wantTo('see welcome page');

        $I->amOnPage('/');

        $I->seeInTitle('Miau-Hau');


    }
}
