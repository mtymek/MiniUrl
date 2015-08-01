<?php

namespace MiniUrl\Service;

use DateTime;
use MiniUrl\Entity\ShortUrl;
use MiniUrl\Exception\InvalidArgumentException;
use MiniUrl\Repository\RepositoryInterface;

class ShortUrlService
{
    const PATH_SEPARATOR = '/';

    /**
     * @var string
     */
    private $domain;

    /**
     * @var RepositoryInterface
     */
    private $shortUrlRepository;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * @param $domain
     * @param RepositoryInterface $shortUrlRepository
     * @param UrlGeneratorInterface|null $generator
     */
    public function __construct(
        $domain,
        RepositoryInterface $shortUrlRepository,
        UrlGeneratorInterface $generator = null
    ) {
        $this->domain = $this->normalizeUrl($domain);
        $this->shortUrlRepository = $shortUrlRepository;

        if (null === $generator) {
            $generator = new UrlGeneratorService();
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
     * @param string $shortPath
     * @return string
     */
    private function formShortUrl($shortPath)
    {
        return $this->domain . self::PATH_SEPARATOR . ltrim($shortPath, self::PATH_SEPARATOR);
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
            $short = $this->formShortUrl($this->generator->generate());
        } while ($unique = $this->shortUrlRepository->findByShortUrl($short));

        $shortUrl = new ShortUrl($longUrl, $short, new DateTime());
        $this->shortUrlRepository->save($shortUrl);

        return $shortUrl;
    }
}
