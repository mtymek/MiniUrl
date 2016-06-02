<?php

namespace MiniUrl\Test\Middleware;

use MiniUrl\Entity\ShortUrl;
use MiniUrl\Middleware\ShortenApiMiddleware;
use MiniUrl\Service\ShortUrlService;
use PHPUnit_Framework_TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class ShortenApiMiddlewareTest extends PHPUnit_Framework_TestCase
{
    public function testShortenReturnsShortenUrl()
    {
        $shortUrlService = $this->prophesize(ShortUrlService::class);
        $shortUrlService->shorten('https://github.com/zendframework/zend-stratigility')
            ->willReturn('squeezed');

        $middleware = new ShortenApiMiddleware($shortUrlService->reveal(), 'http://short.me');

        $request = new ServerRequest([], [], 'http://short.me/shorten');
        $request = $request->withParsedBody(['longUrl' => 'https://github.com/zendframework/zend-stratigility']);
        $response = $middleware($request, new Response());
        $this->assertEquals('http://short.me/squeezed', $response->getBody()->__toString());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testShortenReturns400whenLongUrlIsMissing()
    {
        $shortUrlService = $this->prophesize(ShortUrlService::class);
        $middleware = new ShortenApiMiddleware($shortUrlService->reveal(), 'http://short.me');

        $request = new ServerRequest([], [], 'http://short.me/shorten');
        $request = $request->withParsedBody(['x' => 'y']);
        $response = $middleware($request, new Response());
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testShortenReturns400whenSchemeIsNotHttp()
    {
        $shortUrlService = $this->prophesize(ShortUrlService::class);
        $middleware = new ShortenApiMiddleware($shortUrlService->reveal(), 'http://short.me');

        $request = new ServerRequest([], [], 'http://short.me/shorten');
        $request = $request->withParsedBody(['longUrl' => 'ftp://test.com']);
        $response = $middleware($request, new Response());
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testShortenReturns400whenLongUrlIsInvalid()
    {
        $shortUrlService = $this->prophesize(ShortUrlService::class);
        $middleware = new ShortenApiMiddleware($shortUrlService->reveal(), 'http://short.me');

        $request = new ServerRequest([], [], 'http://short.me/shorten');
        $request = $request->withParsedBody(['longUrl' => 'h://x']);
        $response = $middleware($request, new Response());
        $this->assertEquals(400, $response->getStatusCode());
    }
}
