# AnyComment PHP SEO

AnyComment SEO helps to server renders comments on the page. 

It generates valid HTML and renders on the page for crawlers to index it.
 
Minimum requirement is PHP 5.6.

## How It Works

- First of all, embed this library in your PHP project
- comments rendering code on the page where you would like to index comments 
- the script will do the rest, it will take current page URL
- send requests to AnyComment API to pull latest comments
- generate valid HTML mark-up for these comments 
- output it in `noscript` tag which is not visible to the end user, but seen by crawlers

Internally, library uses cache. Initially it will make HTTP request to AnyComment API, next time it will get value 
from cache.

## Installation 

Add [new package](https://packagist.org/packages/anycomment/php-seo) to `composer.json` in your project directory:

```bash
composer require anycomment/php-seo
```

or


```json
{
  "require":{
    "anycomment/php-seo":"^0.1"
  }
}
```

## Examples 

Examples can be found in `/examples` folder. 

> Notice that you need to provide your API key for each example to make it work.

## Usage 

You need to prepare a configuration class and pass your API key to constructor. 

See example: 

```php
<?php
include __DIR__ . '/../vendor/autoload.php';

use AnyComment\Seo\Comment;

$seo = new Comment('YOU_API_KEY');
echo $seo->render('https://anycomment.io/demo');
```
