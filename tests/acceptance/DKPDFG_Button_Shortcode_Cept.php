<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('test dkpdfg-button shortcode');

$I->loginAsAdmin();
$I->amOnPluginsPage();
$I->activatePlugin('dk-pdf');
$I->activatePlugin('dk-pdf-generator');

/*
As an admin I want to add dkpdfg-button shortcode to a post
so that allows visitors download a PDF with a selection of articles
NOTE: this test only creates the post, you've to manually click pdf button and check the generated PDF.
*/

$I->am( 'admin' );
$I->wantToTest( 'add [dkpdfg-button] shortcode to post content' );
$I->amOnPage( 'wp-admin/post-new.php' );
$I->see( 'Add New Post' );
$I->click( '#publish' );
$I->fillField('#post input[type=text]', 'dkpdfg-button shortcode');
$I->fillField('#post #content', '[dkpdfg-button]<br><br>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.<br><br>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.');
$I->click( '#publish' );
$I->see( 'Post published.' );

/*
PDF button should be shown only in single post, not in list of post (archive page...)
*/
$I->am( 'site visitor' );
$I->amOnPage('/');
$I->see( 'dkpdfg-button shortcode' );
$I->dontSeeElement('#dkpdfg-button');
$I->amOnPage('/dkpdfg-button-shortcode/');
$I->seeElement('#dkpdfg-button');
