<?php

namespace MiniUrl\Service;

use MiniUrl\Exception\InvalidArgumentException;
use MiniUrl\Repository\RepositoryInterface;

class ShortUrlService
{
    const PATH_SEPARATOR = '/';

    /**
     * @var RepositoryInterface
     */
    private $shortUrlRepository;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * @param RepositoryInterface $shortUrlRepository
     * @param UrlGeneratorInterface|null $generator
     */
    public function __construct(
        RepositoryInterface $shortUrlRepository,
        UrlGeneratorInterface $generator = null
    ) {
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
        return rtrim(trim($url), self::PATH_SEPARATOR);
    }

    /**
     * @param string $longUrl
     * @return string
     */
    public function shorten($longUrl)
    {
        if (filter_var($longUrl, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException("'$longUrl' is not valid URL.");
        }

        $longUrl = $this->normalizeUrl($longUrl);
        if ($shortHash = $this->shortUrlRepository->findShortHash($longUrl)) {
            return $shortHash;
        }

        do {
            $shortHash = $this->generator->generate();
        } while ($this->shortUrlRepository->findLongUrl($shortHash));

        $this->shortUrlRepository->save($shortHash, $longUrl);

        return $shortHash;
    }
}
