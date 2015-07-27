<?php

namespace MiniUrl\Middleware;

use MiniUrl\Service\ShortUrlService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ExpandApiMiddleware
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
}
