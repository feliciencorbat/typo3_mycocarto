<?php
declare(strict_types = 1);
namespace Website\Mycocarto\Tests\Acceptance\Frontend;
use Website\Mycocarto\Tests\Acceptance\Support\AcceptanceTester;

class FrontendPagesCest
{
    /**
     * @param AcceptanceTester $I
     */
    public function firstPageIsRendered(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Deuxième article');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function observationsPageIsRendered(AcceptanceTester $I)
    {

        //go to observations et redirect to login
        $I->amOnPage('/observations');
        $I->dontSee("Liste des observations");

        //login as fe user
        $I->fillField('user', "felicien");
        $I->fillField('pass', "password");
        $I->click("Se connecter");

        //show observations page (without report button)
        $I->see('Liste des observations');
        $I->dontSee("Générer rapport");

        //logout
        $I->click("Déconnexion");

        //login as admin fe user
        $I->fillField('user', "felicien_admin");
        $I->fillField('pass', "password");
        $I->click("Se connecter");

        //show observations page (with report button)
        $I->see('Liste des observations');
        $I->see("Générer rapport");
    }
}