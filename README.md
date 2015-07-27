MiniUrl - PSR-7 link minifier
=============================

**Simple URL shortener written in PHP, using PSR-7 & middleware.**

It can be used as a free, open-source replacement for bit.ly's core functionality: creating short links
and redirecting users.

[![Build Status](https://travis-ci.org/mtymek/MiniUrl.svg?branch=master)](https://travis-ci.org/mtymek/MiniUrl)
[![Coverage Status](https://coveralls.io/repos/mtymek/MiniUrl/badge.svg)](https://coveralls.io/r/mtymek/MiniUrl)

Installation
------------

Install this library using composer:

```bash
composer require mtymek/mini-url
```

Usage
-----

There are many ways of using MiniUrl, depending on your needs. You can incorporate it into your app and use it as 
a part of your business logic, or you can use provided middleware to create a website that exposes link shortening
directly to your users.

Before you start, you need to create new instance of `ShortUrlService`, pass base URL used for link generation
(your short domain) and repository that will take care of storing short URLs:

```php
$pdo = new PDO("sqlite:links.db");
$service = new ShortUrlService('http://sho.rt', new PdoRepository($pdo));
```

Short URL service
-----------------

`ShortUrlService` is the foundation of MiniUrl - it is what you want to use when you need to shorten URLs
inside your application logic.

### Shorten link

```php
$url = $service->shorten('http://github.com/zendframework/zend-diactoros');
echo $url->getShortUrl();

// example output: http://sho.rt/Wwr3bMu
```

### Expand

```php
$url = $service->expand('http://sho.rt/ho3nf1');
header('Location: ' . $url->getLongUrl());
```

Middleware
----------

Typically, URL shortener should expose two functionalities: generating short links and redirecting users to full 
URLs. `MiniUrl` comes with handy middleware which makes it extremely easy. Based on PSR-7 standard, they can be
easily wrapped inside other middleware that handles authentication or routing.

### RedirectMiddleware

When user opens short link in his browser, he is expected to be redirected to destination URL. This can be easily 
done using `RedirectMiddleware`. It takes the incoming request, extracts a `path` part from URI (domain and query
are ignored), finds matching long URL and finally redirect user. If link cannot be found in the repository, response 
with 404 code is returned.
 
 Example usage:

```php
$redirector = new RedirectMiddleware($service);

$server = Zend\Diactoros\Server::createServer(
    $redirector,
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);
$server->listen();
```

### ShortenApiMiddleware

`ShortenApiMiddleware` provides implementation of API for shortening links.  

Example usage:

```php
$shortenApi = new ShortenApiMiddleware($service);

$server = Zend\Diactoros\Server::createServer(
    $shortenApi,
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);
$server->listen();
```

You can test it using PHP built-in HTTP server:

    $ cd path-to-api
    $ php -S localhost:8080 shorten-api.php

Create short link using CURL:

```bash
$ curl --data "longUrl=http://mateusztymek.pl/lorem-ipsum-dolor/" http://localhost:8080
http://sho.rt/bloq3y
```

Typically, you will want to wrap `ShortenApi` into another middleware that authorizes your users.

### ExpandApiMiddleware

`ExpandApiMiddleware` provides implementation of API for expanding short links.  

Example usage:

```php
$expandApi = new ExpandApiMiddleware($service);

$server = Zend\Diactoros\Server::createServer(
    $expandApi,
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);
$server->listen();
```

You can test it using PHP built-in HTTP server:

    $ cd path-to-api
    $ php -S localhost:9090 expand-api.php


Expand short link:

```bash
$ curl --data "shortUrl=http://sho.rt/bloq3y" http://localhost:9090
http://mateusztymek.pl/lorem-ipsum-dolor
``` 

See `examples` directory for working code.

Repositories
------------

MiniUrl can be plugged into existing applications using `RepositoryInterface`, that handles storing and
retrieving `ShortUrl` objects:

```php
interface RepositoryInterface
{
    public function findByLongUrl($longUrl);
    public function findByShortUrl($shortUrl);
    public function save(ShortUrl $shortUrl);
}
```

### `PdoRepository`

`PdoRepository` is a universal repository that allows using MiniUrl with any database (queries are very simple...).
In order to use it, you have to pass `PDO` database handle to repository constructor.

```php
$pdo = new PDO("sqlite:links.db");
$service = new ShortUrlService('http://mini.me', new PdoRepository($pdo));
$short = $service->shorten('http://google.com');
echo $short->getShortUrl();
```

Repository will assume that it can access `short_urls` table, with following structure:

```sql
CREATE TABLE short_urls (
  long_url VARCHAR(256) PRIMARY KEY NOT NULL,
  short_url VARCHAR(256) NOT NULL,
  creation_date INT NOT NULL
);
CREATE INDEX short_url_idx ON short_urls(short_url);
```

You can create an empty SQLite database using schema file:

```bash
$ sqlite3 links.db < path/to/miniurl/schema/db-sqlite.sql
```
