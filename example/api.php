<?php
/**
 * MiniUrl example
 *
 * You need to create initial database before using it:
 *
 *    $ sqlite3 links.db < ../schema/db-sqlite.sql
 */

use MiniUrl\Middleware\SimpleApiMiddleware;
use MiniUrl\Repository\PdoRepository;
use MiniUrl\Service\ShortUrlService;

include __DIR__ . '/../vendor/autoload.php';

$pdo = new PDO('sqlite:' . __DIR__ . '/links.db');
$repository = new PdoRepository($pdo);
$service = new ShortUrlService('http://sho.rt', $repository);

$simpleApi = new SimpleApiMiddleware($service);

$server = Zend\Diactoros\Server::createServer(
    $simpleApi,
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);
$server->listen();