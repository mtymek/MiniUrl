<?php

namespace MiniUrl\Service;

interface UrlInterface
{

    /**
     * @param $url
     * @return string
     */
    public function normalizeUrl($url);

    /**
     * @param string $hash
     * @return string
     */
    public function formShortUrl($hash);
}
