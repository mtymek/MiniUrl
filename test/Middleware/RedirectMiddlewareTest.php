<?php

namespace MiniUrl\Test\Middleware;

use DateTime;
use MiniUrl\Entity\ShortUrl;
use MiniUrl\Middleware\RedirectMiddleware;
use MiniUrl\Repository\RepositoryInterface;
use MiniUrl\Service\ShortUrlService;
use PHPUnit_Framework_TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class RedirectMiddlewareTest extends PHPUnit_Framework_TestCase
{
    public function testUserIsRedirectedToLongUrl()
    {
        $shortUrlService = $this->prophesize(RepositoryInterface::class);
        $shortUrlService->findLongUrl('test')
            ->willReturn('http://mateusztymek.pl');

        $middleware = new RedirectMiddleware($shortUrlService->reveal());

        $request = new ServerRequest([], [], 'http://short.me/test');
        $response = $middleware($request, new Response());

        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals('http://mateusztymek.pl', $response->getHeaderLine('Location'));
    }

    public function test404IsReturnedIfShortUrlDoesNotExist()
    {
        $shortUrlService = $this->prophesize(RepositoryInterface::class);
        $shortUrlService->findLongUrl('test')
            ->willReturn(null);

        $middleware = new RedirectMiddleware($shortUrlService->reveal());

        $request = new ServerRequest([], [], 'http://short.me/test');
        $response = $middleware($request, new Response());

        $this->assertEquals(404, $response->getStatusCode());
    }
}
