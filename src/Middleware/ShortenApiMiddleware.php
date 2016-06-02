<?php

namespace MiniUrl\Middleware;

use MiniUrl\Service\ShortUrlService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ShortenApiMiddleware
{
    /** @var ShortUrlService */
    private $shortUrlService;

    /** @var string */
    private $domain;

    public function __construct(ShortUrlService $shortUrlService, $domain)
    {
        $this->shortUrlService = $shortUrlService;
        $this->domain = $domain;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $params = (array)$request->getParsedBody();
        if (!isset($params['longUrl'])) {
            return $response->withStatus(400);
        }

        $parts = parse_url($params['longUrl']);
        if (!$parts || !isset($parts['scheme']) || !in_array($parts['scheme'], ['http', 'https'])) {
            return $response->withStatus(400);
        }

        $response->getBody()->write($this->domain . '/' . $this->shortUrlService->shorten($params['longUrl']));

        return $response->withStatus(200);
    }
}
