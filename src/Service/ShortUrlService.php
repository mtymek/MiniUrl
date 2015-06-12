<?php

namespace MiniUrl\Service;

use DateTime;
use MiniUrl\Entity\ShortUrl;
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

    private function normalizeUrl($url)
    {
        return rtrim(trim($url), self::PATH_SEPARATOR);
    }

    /**
     * @param string $shortPath
     * @return string
     */
    private function formShortUrl($shortPath)
    {
        return $this->domain . self::PATH_SEPARATOR . $shortPath;
    }

    public function shorten($longUrl)
    {
        $longUrl = $this->normalizeUrl($longUrl);
        if ($shortUrl = $this->shortUrlRepository->findByLongUrl($longUrl)) {
            return $shortUrl;
        }

        $shortUrl = new ShortUrl();
        $shortUrl->setFullUrl($longUrl);
        $shortUrl->setCreationDate(new DateTime());

        do {
            $short = $this->formShortUrl($this->generator->generate());
        } while ($unique = $this->shortUrlRepository->findByShortUrl($short));

        return $shortUrl;
    }
}
