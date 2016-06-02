<?php

namespace MiniUrl\Repository;

/**
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
     *
     * @return string
     */
    public function findShortHash($longUrl)
    {
        if (!isset($this->urls[$longUrl])) {
            return null;
        }
        return $this->urls[$longUrl];
    }

    /**
     * @param $shortHash
     *
     * @return string
     */
    public function findLongUrl($shortHash)
    {
        if ($key = array_search($shortHash, $this->urls)) {
            return $key;
        }
        return null;
    }

    /**
     * @param $shortHash
     * @param $longUrl
     */
    public function save($shortHash, $longUrl)
    {
        $this->urls[$longUrl] = $shortHash;
        $data = '';
        foreach ($this->urls as $long => $short) {
            $data[] = "$long\t$short\n";
        }
        file_put_contents($this->fileName, $data);
    }
}
