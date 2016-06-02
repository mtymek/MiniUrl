<?php

namespace MiniUrl\Middleware;

use MiniUrl\Repository\RepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ExpandApiMiddleware
{
    /**
     * @var RepositoryInterface
     */
    private $shortUrlRepository;

    public function __construct(RepositoryInterface $shortUrlService)
    {
        $this->shortUrlRepository = $shortUrlService;
    }

    private function normalizePath($path)
    {
        return trim($path, '/');
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
        $long = $this->shortUrlRepository->findLongUrl($this->normalizePath($parts['path']));

        $response->getBody()->write($long);

        return $response->withStatus(200);
    }
}
