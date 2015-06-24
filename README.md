MiniUrl - PSR-7 link minifier
=============================

Simple URL shortener, written in PHP that uses PSR-7.

Usage
-----

Shorten link:

```php
$service = new ShortUrlService('http://sho.rt', new PdoRepository($pdo));
$short = $service->shorten('http://github.com/zendframework/zend-diactoros');
echo $short->getShortUrl();
```

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

You can create empty SQLite database using schema file:

```bash
$ sqlite3 links.db < path/to/miniurl/schema/db-sqlite.sql
```
