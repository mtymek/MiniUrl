<?php

namespace MiniUrl\Repository;

use PDO;

/**
 * Example repository that stores short URLs in SQL database
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
     *
     * @return string
     */
    public function findShortHash($longUrl)
    {
        $q = "SELECT short_hash FROM short_urls WHERE long_url=:long_url LIMIT 1";
        $stmt = $this->pdo->prepare($q);
        $stmt->execute(['long_url' => $longUrl]);
        if (!$row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return null;
        }

        return $row['short_hash'];
    }

    /**
     * @param $shortHash
     *
     * @return string
     */
    public function findLongUrl($shortHash)
    {
        $q = "SELECT long_url FROM short_urls WHERE short_hash=:short_hash LIMIT 1";
        $stmt = $this->pdo->prepare($q);
        $stmt->execute(['short_hash' => $shortHash]);
        if (!$row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return null;
        }

        return $row['long_url'];
    }

    /**
     * @param $shortHash
     * @param $longUrl
     */
    public function save($shortHash, $longUrl)
    {
        $q = "INSERT INTO short_urls(long_url, short_hash) VALUES(:long_url, :short_hash)";
        $stmt = $this->pdo->prepare($q);
        $stmt->execute([
            'long_url' => $longUrl,
            'short_hash' => $shortHash,
        ]);
    }
}
