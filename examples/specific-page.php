<?php
/**
 * Load comments for specific page by providing its URL.
 */
include __DIR__ . '/../vendor/autoload.php';


use AnyComment\Seo\Comment;

$seo = new Comment('YOU_API_KEY');
var_dump($seo->render('https://anycomment.io/demo'));
