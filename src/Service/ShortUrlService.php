<?php

namespace MiniUrl\Service;

use DateTime;
use MiniUrl\Entity\ShortUrl;
use MiniUrl\Exception\InvalidArgumentException;
use MiniUrl\Repository\RepositoryInterface;

class ShortUrlService
{
    /**
     * @var RepositoryInterface
     */
    private $shortUrlRepository;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * @var UrlService
     */
    private $url;

    /**
     * @param $domain
     * @param RepositoryInterface $shortUrlRepository
     * @param UrlGeneratorInterface|null $generator
     */
    public function __construct(
        UrlService $url,
        RepositoryInterface $shortUrlRepository,
        UrlGeneratorInterface $generator = null
    ) {
        $this->shortUrlRepository = $shortUrlRepository;

        if (null === $generator) {
            $factory = new RandomLibFactory();
            $randomizer = $factory->getMediumStrengthGenerator();
            $generator = new UrlGeneratorService($randomizer);
        }
        $this->generator = $generator;
    }

    /**
     * @param string $url
     * @return string
     */
    private function normalizeUrl($url)
    {
        return rtrim($url, self::PATH_SEPARATOR);
    }

    /**
     * @param string $hash
     * @return string
     */
    private function formShortUrl($hash)
    {
        return $this->domain . self::PATH_SEPARATOR . $hash;
    }

    /**
     * @param string $path
     * @return ShortUrl|null
     */
    public function expand($path)
    {
        return $this->shortUrlRepository->findByShortUrl($this->formShortUrl($path));
    }

    /**
     * @param string $longUrl
     * @return ShortUrl|null
     */
    public function shorten($longUrl)
    {
        if (filter_var($longUrl, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException("'$longUrl' is not valid URL.");
        }

        $longUrl = $this->normalizeUrl($longUrl);
        if ($shortUrl = $this->shortUrlRepository->findByLongUrl($longUrl)) {
            return $shortUrl;
        }

        do {
            $hash = $this->generator->generate();
        } while ($unique = $this->shortUrlRepository->findByShortUrl($hash));

        $this->shortUrlRepository->save($longUrl, $hash, new DateTime());

        return $shortUrl;
    }
}
