<?php

namespace MiniUrl\Service;

use RandomLib\Factory as RandomLibFactory;

class UrlGeneratorService implements UrlGeneratorInterface
{
    const LENGTH = 7;

    const ACCEPTED_CHARACTERS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    private $randomizer;

    public function __construct($randomizer)
    {
        $this->randomizer = $randomizer;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return $this->randomizer->generateString(self::LENGTH, self::ACCEPTED_CHARACTERS);
    }
}
