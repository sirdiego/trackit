<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('use a stopwatch');
$I->amOnPage('/');
$I->canSee('start');
$I->click('start');
$I->canSee('Stopwatch started here.');
$I->canSee('stop');
$I->click('stop');
$I->canSee('Stopped.');
