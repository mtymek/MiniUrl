<?php

namespace MiniUrl\Repository;

use MiniUrl\Entity\ShortUrl;

class FileRepository implements RepositoryInterface
{
    private $data;

    public function __construct($fileName)
    {

    }

    /**
     * @param $longUrl
     * @return ShortUrl|null
     */
    public function findByLongUrl($longUrl)
    {
        // TODO: Implement findByLongUrl() method.
    }

    /**
     * @param $shortUrl
     * @return ShortUrl|null
     */
    public function findByShortUrl($shortUrl)
    {
        // TODO: Implement findByShortUrl() method.
    }

    /**
     * @param ShortUrl $shortUrl
     * @return void
     */
    public function save(ShortUrl $shortUrl)
    {
        // TODO: Implement save() method.
    }
}
