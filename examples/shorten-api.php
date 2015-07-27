<?php
/**
 * MiniUrl example
 *
 * You need to create initial database before using it:
 *
 *    $ sqlite3 links.db < ../schema/db-sqlite.sql
 */

use MiniUrl\Middleware\ShortenApiMiddleware;
use MiniUrl\Repository\PdoRepository;
use MiniUrl\Service\ShortUrlService;

include __DIR__ . '/../vendor/autoload.php';

$pdo = new PDO('sqlite:' . __DIR__ . '/links.db');
$repository = new PdoRepository($pdo);
$service = new ShortUrlService('http://sho.rt', $repository);

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
