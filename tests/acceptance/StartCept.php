<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('assure start page');
$I->amOnPage('/');
$I->canSee('trackit controller');

