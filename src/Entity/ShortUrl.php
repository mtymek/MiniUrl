<?php

namespace MiniUrl\Entity;

use DateTime;

class ShortUrl
{
    /**
     * @var string
     */
    protected $longUrl;

    /**
     * @var string
     */
    protected $shortUrl;

    /**
     * @var DateTime
     */
    protected $creationDate;

    public function __construct($longUrl = null, $shortUrl = null, $creationDate = null)
    {
        $this->longUrl = $longUrl;
        $this->shortUrl = $shortUrl;
        $this->creationDate = $creationDate;
    }

    /**
     * @return string
     */
    public function getLongUrl()
    {
        return $this->longUrl;
    }

    /**
     * @param string $longUrl
     */
    public function setLongUrl($longUrl)
    {
        $this->longUrl = $longUrl;
    }

    /**
     * @return string
     */
    public function getShortUrl()
    {
        return $this->shortUrl;
    }

    /**
     * @param string $shortUrl
     */
    public function setShortUrl($shortUrl)
    {
        $this->shortUrl = $shortUrl;
    }

    /**
     * @return DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param DateTime $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }
}
