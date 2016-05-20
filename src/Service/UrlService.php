<?php

namespace MiniUrl\Service;

use DateTime;
use MiniUrl\Entity\ShortUrl;
use MiniUrl\Exception\InvalidArgumentException;
use MiniUrl\Repository\RepositoryInterface;

class UrlService
{
    /**
     * @var string
     */
    private $domain;

    /**
     * ShortUrlService constructor.
     *
     * @param $domain
     */
    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @param string $url
     * @return string
     */
    public function normalizeUrl($url)
    {
        return rtrim($url, DIRECTORY_SEPARATOR);
    }

    /**
     * @param string $hash
     * @return string
     */
    public function formShortUrl($hash)
    {
        return $this->domain . DIRECTORY_SEPARATOR . $hash;
    }
}
