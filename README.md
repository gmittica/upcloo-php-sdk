# UpCloo PHP SDK [![Build Status](https://secure.travis-ci.org/wdalmut/upcloo-php-sdk.png)](http://travis-ci.org/wdalmut/upcloo-php-sdk?branch=master)

This library is intended for working with PHP 5.2+

## Getting started

```php
<?php
$manager = UpCloo_Manager::getInstance();
//Setting up credentials
$manager->setCredential("username", "password", "sitekey");

//Index a new content
$manager->index(
    array(
        'id' => '1243',
        'title' => 'Hello world',
        'link' => 'http://my-domain.ltd/hello-world'
    )
);

//Get correlation of the indexed content
$manager->get("1243");
```

See [wiki pages](upcloo-php-sdk/wiki) for more information.

## Search Queries

Now search query are handled by this library 

```php
$searchQuery = $manager->search()->query("Text to search");

$results = $manager->get($searchQuery);
```

### Complex queries

Search queries works chaining objects. You can start a new query
using ```search()``` method.

```php
$searchQuery = $manager->search()
    ->query("Text to search")
    ->facet("category");
    ->range() //maybe much more complex
    ->filterBy("category", "Web")
    ->network("a-partner-sitekey")
    ->network("a-partner-sitekey")
;

$results = $manager->get($searchQuery);
```

### Ranges 

Here the range method prototype

```php
public function range($type=self::RANGE_DATE, 
            $field="publish_date", 
            $gap="1", 
            $direction=self::DIRECTION_FORWARD, 
            $from="1900-01-01T00:00:00Z", 
            $to=self::NOW, 
            $value=self::RANGE_DATE_YEAR);
```

Using into a call

```php
//Backward range query
$searchQuery = $manager->search()
    ->query("Text to search")
    ->range("date", "publish_date", 2, "-", "NOW", "2000-01-01T00:00:00Z")
    ->filterBy("category", "meteo")
;

$results = $manager->get($searchQuery);
```

## Library autoloader

This library provides a simple autoloader. Simple you have
to set your ```include_path``` with the library position
and all your other dependencies. After that you can 
require for ```UpCloo/Autoloader.php```. That's it. See this
running example:

```php
<?php
set_include_path(
    implode(
        PATH_SEPARATOR,  
        array(
            //UpCloo library position on disk
            realpath(dirname(__FILE__) . '/../src'),
            //include other libraries previously setted
            get_include_path()
        )
    )
);

require_once 'UpCloo/Autoloader.php';

//now the library is ready.
```

## How to use the PHAR package

First of all download the latest phar archive from the download section.
After that you can use like this:

```php
<?php 
require_once 'upcloo-sdk.phar';

$manager = UpCloo_Manager::getInstance();
$manager->setCredential("username", "sitekey", "password");

```

Consider that the PHAR archive autoload all the library by it self.

## Running Tests & Reports

If you want to run tests and get the reports of coverage you can
simply use the ```phpunit```.

For more information on ```phpunit``` consider 
[the project page](http://www.phpunit.de/manual/current/en/) 

```
$ phpunit
```

