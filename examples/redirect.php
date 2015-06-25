<?php
/**
 * MiniUrl example
 *
 * You need to create initial database before using it:
 *
 *    $ sqlite3 links.db < ../schema/db-sqlite.sql
 */

use MiniUrl\Middleware\RedirectMiddleware;
use MiniUrl\Repository\PdoRepository;
use MiniUrl\Service\ShortUrlService;

include __DIR__ . '/../vendor/autoload.php';

$pdo = new PDO("sqlite:links.db");
$service = new ShortUrlService('http://sho.rt', new PdoRepository($pdo));
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
