<?php

namespace MiniUrl\Repository;

use MiniUrl\Entity\ShortUrl;

interface RepositoryInterface
{
    /**
     * @param $longUrl
     * @return ShortUrl|null
     */
    public function findByLongUrl($longUrl);

    /**
     * @param $shortUrl
     * @return ShortUrl|null
     */
    public function findByShortUrl($shortUrl);

    /**
     * @param ShortUrl $shortUrl
     * @return void
     */
    public function save(ShortUrl $shortUrl);
}
