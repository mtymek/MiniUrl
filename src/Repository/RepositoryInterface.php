<?php

namespace MiniUrl\Repository;

interface RepositoryInterface
{
    /**
     * @param $longUrl
     * @return string
     */
    public function findShortHash($longUrl);

    /**
     * @param $shortHash
     *
     * @return string
     */
    public function findLongUrl($shortHash);

    /**
     * @param $shortHash
     * @param $longUrl
     */
    public function save($shortHash, $longUrl);
}
