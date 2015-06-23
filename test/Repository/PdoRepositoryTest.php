<?php

namespace MiniUrl\Test\Repository;

use MiniUrl\Entity\ShortUrl;
use MiniUrl\Repository\PdoRepository;
use MiniUrl\Service\ShortUrlService;
use PDO;
use PHPUnit_Extensions_Database_DataSet_IDataSet;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;
use PHPUnit_Extensions_Database_TestCase;

class PdoRepositoryTest extends PHPUnit_Extensions_Database_TestCase
{
    private $pdo;
    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        if (null === $this->pdo) {
            $this->pdo = new PDO('sqlite::memory:');
            $this->pdo->exec(file_get_contents(__DIR__ . '/../../schema/db-sqlite.sql'));
        }
        return $this->createDefaultDBConnection($this->pdo);
    }

    /**
     * Returns the test dataset.
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/../data/shortlinks-seed.xml');
    }

    public function testFindByLongUrl()
    {
        $conn = $this->getConnection()->getConnection();
        $repo = new PdoRepository($conn);
        $url = $repo->findByLongUrl('http://google.com');
        $this->assertInstanceOf(ShortUrl::class, $url);
        $this->assertEquals('http://mini.me/w1kheu', $url->getShortUrl());
        $this->assertEquals('http://google.com', $url->getLongUrl());
    }

    public function testSave()
    {
//        $t = time();
//        $shortUrl = new ShortUrl("http://mateusztymek.pl", "http://sho.rt/mat", $t);
//        $repo = new PdoRepository($this->getConnection()->getConnection());
//        $repo->save($shortUrl);
//
//        $this->assertDataSetsEqual($this->createArrayDataSet([
//
//        ]));
    }
}