<?php

namespace MiniUrl\Middleware;

use MiniUrl\Repository\RepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RedirectMiddleware
{
    /**
     * @var RepositoryInterface
     */
    private $shortUrlRepository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->shortUrlRepository = $repository;
    }

    private function normalizePath($path)
    {
        return trim($path, '/');
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $url = $this->shortUrlRepository->findLongUrl(
            $this->normalizePath($request->getUri()->getPath())
        );

        if (!$url) {
            return $response->withStatus(404);
        }

        $response = $response->withStatus(301)
            ->withHeader('Location', $url);

        return $response;
    }
}
