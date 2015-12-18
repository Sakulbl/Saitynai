<?php
$I = new ApiTester($scenario);
$I->wantTo('get character data via API');
$I->amHttpAuthenticated('test1', 'test123');
//$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendGET('/getChar');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('{"result":"ok"}');
?>