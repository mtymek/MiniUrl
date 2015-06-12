<?php

namespace MiniUrl\Service;

use RandomLib\Factory as RandomLibFactory;

class UrlGeneratorService implements UrlGeneratorInterface
{
    const LENGTH = 6;

    const ACCEPTED_CHARACTERS = 'abcdefghijklmnopqrstuvwxyz0123456789';

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
