<?php

namespace MiniUrl\Service;

use MiniUrl\Entity\ShortUrl;

class ShortUrlService
{
    public function create($longUrl)
    {
        return new ShortUrl();
    }
}
