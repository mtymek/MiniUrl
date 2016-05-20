<?php

namespace MiniUrl\Service;

/**
 * Class UrlService
 */
class UrlService implements UrlInterface
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
