<?php

namespace MiniUrl\Repository;

use MiniUrl\Entity\ShortUrl;

/**
 * Class FileRepository
 * Don't use this class - it was created to help testing this library
 * @codeCoverageIgnore
 */
class FileRepository implements RepositoryInterface
{
    private $urls = [];

    private $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
        if (!file_exists($fileName)) {
            return;
        }
        $f = file($fileName);
        foreach ($f as $line) {
            if (strlen($line) == 0) {
                continue;
            }
            list($long, $short) = explode("\t", $line);
            $this->urls[trim($long)] = trim($short);
        }
    }

    /**
     * @param $longUrl
     * @return ShortUrl|null
     */
    public function findByLongUrl($longUrl)
    {
        if (!isset($this->urls[$longUrl])) {
            return null;
        }
        return new ShortUrl($longUrl, $this->urls[$longUrl]);
    }

    /**
     * @param $shortUrl
     * @return ShortUrl|null
     */
    public function findByShortUrl($shortUrl)
    {
        if ($key = array_search($shortUrl, $this->urls)) {
            return new ShortUrl($key, $shortUrl);
        }
        return null;
    }

    /**
     * @param ShortUrl $shortUrl
     * @return void
     */
    public function save(ShortUrl $shortUrl)
    {
        $this->urls[$shortUrl->getLongUrl()] = $shortUrl->getShortUrl();
        $data = '';
        foreach ($this->urls as $long => $short) {
            $data[] = "$long\t$short\n";
        }
        file_put_contents($this->fileName, $data);
    }
}
