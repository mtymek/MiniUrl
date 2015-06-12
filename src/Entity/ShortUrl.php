<?php

namespace MiniUrl\Entity;

use DateTime;

class ShortUrl
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $fullUrl;

    /**
     * @var string
     */
    protected $shortUrl;

    /**
     * @var DateTime
     */
    protected $creationDate;

    public function __construct($fullUrl = null, $shortUrl = null, $creationDate = null)
    {
        $this->fullUrl = $fullUrl;
        $this->shortUrl = $shortUrl;
        $this->creationDate = $creationDate;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFullUrl()
    {
        return $this->fullUrl;
    }

    /**
     * @param string $fullUrl
     */
    public function setFullUrl($fullUrl)
    {
        $this->fullUrl = $fullUrl;
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
