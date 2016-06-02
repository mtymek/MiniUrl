<?php

namespace MiniUrl\Test\Repository;

use DateTime;
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

    public function testFindShortUrl()
    {
        $conn = $this->getConnection()->getConnection();
        $repo = new PdoRepository($conn);
        $url = $repo->findShortHash('http://google.com');
        $this->assertEquals('w1kheu', $url);
    }

    public function testFindShortUrlReturnsNullIfUrlDoesNotExist()
    {
        $conn = $this->getConnection()->getConnection();
        $repo = new PdoRepository($conn);
        $url = $repo->findShortHash('http://yahoo.com');
        $this->assertNull($url);
    }

    public function testFindLongUrl()
    {
        $conn = $this->getConnection()->getConnection();
        $repo = new PdoRepository($conn);
        $url = $repo->findLongUrl('obc5gs');
        $this->assertEquals('http://github.com/zendframework', $url);
    }

    public function testFindLongUrlReturnsNullIfUrlDoesNotExist()
    {
        $conn = $this->getConnection()->getConnection();
        $repo = new PdoRepository($conn);
        $url = $repo->findLongUrl('none');
        $this->assertNull($url);
    }

    public function testSave()
    {
        $repo = new PdoRepository($this->getConnection()->getConnection());
        $repo->save("mat", "http://mateusztymek.pl");

        $this->assertEquals(3, $this->getConnection()->getRowCount('short_urls'));
        $loaded = $repo->findLongUrl('mat');
        $this->assertEquals("http://mateusztymek.pl", $loaded);
    }
}
