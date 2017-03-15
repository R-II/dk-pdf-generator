<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('test read more tag');

$I->loginAsAdmin();
/*
$I->amOnPluginsPage();
$I->activatePlugin('dk-pdf');
$I->activatePlugin('dk-pdf-generator');
*/

$I->am( 'admin' );
$I->wantToTest( 'see all the content in the PDF on posts with read more tag' );
$I->amOnPage( 'wp-admin/post-new.php' );
$I->see( 'Add New Post' );
$I->click( '#publish' );
$I->fillField('#post input[type=text]', 'read more tag');
$I->fillField('#post #content', 'Lorem ipsum<br><!--more--><br>Dolor sit amet');
$I->click( '#publish' );
$I->see( 'Post published.' );
