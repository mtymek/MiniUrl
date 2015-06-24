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
     * @return string
     */
    public function getShortUrl()
    {
        return $this->shortUrl;
    }

    /**
     * @return DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
}
