<?php

namespace MiniUrl\Middleware;

use InvalidArgumentException;
use MiniUrl\Service\ShortUrlService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Uri;

class SimpleApiMiddleware
{
    /**
     * @var ShortUrlService
     */
    private $shortUrlService;

    /**
     * @param ShortUrlService $shortUrlService
     */
    public function __construct(ShortUrlService $shortUrlService)
    {
        $this->shortUrlService = $shortUrlService;
    }

    public function shorten(ServerRequestInterface $request, ResponseInterface $response)
    {
        $params = (array)$request->getParsedBody();
        if (!isset($params['longUrl'])) {
            return $response->withStatus(400);
        }

        $parts = parse_url($params['longUrl']);
        if (!$parts || !isset($parts['scheme']) || !in_array($parts['scheme'], ['http', 'https'])) {
            return $response->withStatus(400);
        }

        $response->getBody()->write($this->shortUrlService->shorten($params['longUrl'])->getShortUrl());

        return $response->withStatus(200);
    }

    public function expand(ServerRequestInterface $request, ResponseInterface $response)
    {
        $params = (array)$request->getParsedBody();
        if (!isset($params['shortUrl'])) {
            return $response->withStatus(400);
        }

        $parts = parse_url($params['shortUrl']);
        if (!$parts || !isset($parts['scheme']) || !in_array($parts['scheme'], ['http', 'https'])) {
            return $response->withStatus(400);
        }
        $long = $this->shortUrlService->expand($parts['path'])->getLongUrl();

        $response->getBody()->write($long);

        return $response->withStatus(200);
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $path = trim($request->getUri()->getPath(), '/');

        if ($path == 'shorten') {
            return $this->shorten($request, $response);
        } elseif ($path == 'expand') {
            return $this->expand($request, $response);
        }

        return $response->withStatus(404);
    }
}
