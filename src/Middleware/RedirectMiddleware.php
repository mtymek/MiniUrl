<?php

namespace MiniUrl\Middleware;

use MiniUrl\Service\ShortUrlService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RedirectMiddleware
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

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $url = $this->shortUrlService->expand($request->getUri()->getPath());

        if (!$url) {
            return $response->withStatus(404);
        }

        $response = $response->withStatus(301)
            ->withHeader('Location', $url->getLongUrl());

        return $response;
    }
}
