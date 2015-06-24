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

        try {
            $uri = new Uri($params['longUrl']);
        } catch (InvalidArgumentException $e) {
            return $response->withStatus(400);
        }

        $response->getBody()->write($this->shortUrlService->shorten($uri->__toString())->getShortUrl());

        return $response;
    }

    public function expand(ServerRequestInterface $request, ResponseInterface $response)
    {
        $params = (array)$request->getParsedBody();
        if (!isset($params['shortUrl'])) {
            return $response->withStatus(400);
        }

        try {
            $uri = new Uri($params['shortUrl']);
        } catch (InvalidArgumentException $e) {
            return $response->withStatus(400);
        }

        $response->getBody()->write($this->shortUrlService->expand($uri->getPath())->getLongUrl());

        return $response;
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
