<?php

namespace MiniUrl\Test\Middleware;

use MiniUrl\Entity\ShortUrl;
use MiniUrl\Middleware\ExpandApiMiddleware;
use MiniUrl\Repository\RepositoryInterface;
use MiniUrl\Service\ShortUrlService;
use PHPUnit_Framework_TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class ExpandApiMiddlewareTest extends PHPUnit_Framework_TestCase
{
    public function testExpandReturnsLongUrl()
    {
        $shortUrlRepository = $this->prophesize(RepositoryInterface::class);
        $shortUrlRepository->findLongUrl('squeezed')
            ->willReturn('http://long-url.com/path/to/image.jpg');

        $middleware = new ExpandApiMiddleware($shortUrlRepository->reveal());

        $request = new ServerRequest([], [], 'http://short.me/expand');
        $request = $request->withParsedBody(['shortUrl' => 'http://short.me/squeezed']);
        $response = $middleware($request, new Response());
        $this->assertEquals('http://long-url.com/path/to/image.jpg', $response->getBody()->__toString());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testExpandReturns400whenShortUrlIsMissing()
    {
        $shortUrlRepository = $this->prophesize(RepositoryInterface::class);
        $middleware = new ExpandApiMiddleware($shortUrlRepository->reveal());

        $request = new ServerRequest([], [], 'http://short.me/expand');
        $request = $request->withParsedBody(['x' => 'y']);
        $response = $middleware($request, new Response());
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testExpandReturns400whenShortUrlIsInvalid()
    {
        $shortUrlRepository = $this->prophesize(RepositoryInterface::class);
        $middleware = new ExpandApiMiddleware($shortUrlRepository->reveal());

        $request = new ServerRequest([], [], 'http://short.me/expand');
        $request = $request->withParsedBody(['shortUrl' => 'h://x']);
        $response = $middleware($request, new Response());
        $this->assertEquals(400, $response->getStatusCode());
    }
}
