<?php
/**
 * Automatically grab current page URL.
 */
include __DIR__ . '/../vendor/autoload.php';


use AnyComment\Seo\Comment;
use AnyComment\Seo\Http\Request;

$seo = new Comment('YOU_API_KEY');
var_dump($seo->render((new Request)->getCurrentUrl()));
