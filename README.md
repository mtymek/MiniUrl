MiniUrl - PSR-7 link minifier
=============================

Simple URL shortener, written in PHP that uses PSR-7.


Usage
-----

Shorten link:

```php
$short = $service->shorten('http://github.com/zendframework/zend-diactoros');
echo $short->getShortUrl();
```

Repositories
------------

MiniUrl can be plugged to any 

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
Usage is very simple - only thing you need to pass is `PDO` database handle.

```php
$pdo = new PDO("sqlite:links.db");
$service = new ShortUrlService('http://mini.me', new PdoRepository($pdo));
$short = $service->shorten('http://google.com');
echo $short->getShortUrl();
```

You can create empty SQLite database using schema file:

```bash
$ sqlite3 links.db < path/to/miniurl/schema/db-sqlite.sql
```
