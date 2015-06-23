<?php

namespace MiniUrl\Repository;

use MiniUrl\Entity\ShortUrl;
use MiniUrl\Service\ShortUrlService;
use PDO;

/**
 * Class FileRepository
 * For tests only!
 */
class PdoRepository implements RepositoryInterface
{
    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param $longUrl
     * @return ShortUrl|null
     */
    public function findByLongUrl($longUrl)
    {
        $q = "SELECT * FROM short_url WHERE long_url=:long_url LIMIT 1";
        $stmt = $this->pdo->prepare($q);
        $stmt->execute(['long_url' => $longUrl]);
        if (!$row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return null;
        }

        return new ShortUrl($row['long_url'], $row['short_url'], $row['creation_date']);
    }

    /**
     * @param $shortUrl
     * @return ShortUrl|null
     */
    public function findByShortUrl($shortUrl)
    {
        $q = "SELECT * FROM short_url WHERE short_url=:short_url LIMIT 1";
        $stmt = $this->pdo->prepare($q);
        $stmt->execute(['short_url' => $shortUrl]);
        if (!$row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return null;
        }

        return new ShortUrl($row['long_url'], $row['short_url'], $row['creation_date']);
    }

    /**
     * @param ShortUrl $shortUrl
     * @return void
     */
    public function save(ShortUrl $shortUrl)
    {
        $q = "INSERT INTO short_url(long_url, short_url, creation_date) VALUES(:long, :short, :date)";
        $stmt = $this->pdo->prepare($q);
        $stmt->execute([
            'long' => $shortUrl->getLongUrl(),
            'short' => $shortUrl->getShortUrl(),
            'date' => $shortUrl->getCreationDate()->getTimestamp(),
        ]);
    }
}
