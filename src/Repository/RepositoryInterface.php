<?php

namespace MiniUrl\Repository;

use MiniUrl\Entity\ShortUrl;
use DateTime;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface
{
    /**
     * @param $longUrl
     * @return String
     */
    public function findByLongUrl($longUrl);

    /**
     * @param $shortUrl
     * @return string
     */
    public function findByShortUrl($shortUrl);

    /**
     * @param String   $shortHash
     * @param String   $url
     * @param DateTime $createdAt
     *
     * @return void
     */
    public function save($shortHash, $url, DateTime $createdAt);
}
