<?php
/**
 * MiniUrl example
 *
 * You need to create initial database before using it:
 *
 *    $ sqlite3 links.db < ../schema/db-sqlite.sql
 */

use MiniUrl\Repository\PdoRepository;

include __DIR__ . '/../vendor/autoload.php';

$pdo = new PDO('sqlite:' . __DIR__ . '/links.db');
$repository = new PdoRepository($pdo);
$service = new \MiniUrl\Service\ShortUrlService('http://sho.rt', $repository);

$url = $service->shorten("http://github.com/zendframework/zend-diactoros");
echo "{$url->getLongUrl()} is now shorten to {$url->getShortUrl()}.\n";