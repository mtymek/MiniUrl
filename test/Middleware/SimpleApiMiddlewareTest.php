<?php

namespace MiniUrl\Test\Middleware;

use DateTime;
use MiniUrl\Entity\ShortUrl;
use MiniUrl\Middleware\RedirectMiddleware;
use MiniUrl\Middleware\SimpleApiMiddleware;
use MiniUrl\Service\ShortUrlService;
use PHPUnit_Framework_TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class SimpleApiMiddlewareTest extends PHPUnit_Framework_TestCase
{
    public function testApiReturns404forUnknownPath()
    {
        $shortUrlService = $this->prophesize(ShortUrlService::class);

        $middleware = new SimpleApiMiddleware($shortUrlService->reveal());

        $request = new ServerRequest([], [], 'http://short.me/test');
        $response = $middleware($request, new Response());

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testExpandReturnsLongUrl()
    {
        $shortUrlService = $this->prophesize(ShortUrlService::class);
        $shortUrlService->expand('/squeezed')
            ->willReturn(new ShortUrl('http://long-url.com/path/to/image.jpg', 'http://short.me/squeezed'));

        $middleware = new SimpleApiMiddleware($shortUrlService->reveal());

        $request = new ServerRequest([], [], 'http://short.me/expand');
        $request = $request->withParsedBody(['shortUrl' => 'http://short.me/squeezed']);
        $response = $middleware($request, new Response());
        $this->assertEquals('http://long-url.com/path/to/image.jpg', $response->getBody()->__toString());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testExpandReturns400whenShortUrlIsMissing()
    {
        $shortUrlService = $this->prophesize(ShortUrlService::class);
        $middleware = new SimpleApiMiddleware($shortUrlService->reveal());

        $request = new ServerRequest([], [], 'http://short.me/expand');
        $request = $request->withParsedBody(['x' => 'y']);
        $response = $middleware($request, new Response());
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testExpandReturns400whenShortUrlIsInvalid()
    {
        $shortUrlService = $this->prophesize(ShortUrlService::class);
        $middleware = new SimpleApiMiddleware($shortUrlService->reveal());

        $request = new ServerRequest([], [], 'http://short.me/expand');
        $request = $request->withParsedBody(['shortUrl' => 'h://x']);
        $response = $middleware($request, new Response());
        $this->assertEquals(400, $response->getStatusCode());
    }
}