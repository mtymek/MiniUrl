<?php

namespace MiniUrl\Service;

use RandomLib\Factory as RandomLibFactory;

class UrlGeneratorService implements UrlGeneratorInterface
{
    const LENGTH = 7;

    const ACCEPTED_CHARACTERS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * @return string
     */
    public function generate()
    {
        $factory = new RandomLibFactory();
        $generator = $factory->getMediumStrengthGenerator();
        return $generator->generateString(self::LENGTH, self::ACCEPTED_CHARACTERS);
    }
}
