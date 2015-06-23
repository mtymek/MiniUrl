<?php

namespace MiniUrl\Test\Service;

use MiniUrl\Entity\ShortUrl;
use MiniUrl\Repository\RepositoryInterface;
use MiniUrl\Service\ShortUrlService;
use MiniUrl\Service\UrlGeneratorInterface;
use PHPUnit_Framework_TestCase;
use Prophecy\Argument;

class ShortUrlServiceTest extends PHPUnit_Framework_TestCase
{
    public function testShortenReturnsPreviouslyShortenedUrl()
    {
        $repository = $this->prophesize(RepositoryInterface::class);
        $shortUrl = new ShortUrl();
        $repository->findByLongUrl('http://long-url.com/pa/t/h')->willReturn($shortUrl);
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $service = new ShortUrlService('http://sho.rt/', $repository->reveal(), $urlGenerator->reveal());
        $ret = $service->shorten('http://long-url.com/pa/t/h');
        $this->assertSame($shortUrl, $ret);
    }

    public function testShortenShortensUrl()
    {
        $repository = $this->prophesize(RepositoryInterface::class);
        $repository->findByLongUrl('http://long-url.com/pa/t/h')->willReturn(null);
        $repository->findByShortUrl('http://sho.rt/abcd89')->willReturn(null);
        $repository->save(Argument::type(ShortUrl::class))->shouldBeCalled();
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->generate()->willReturn('abcd89');
        $service = new ShortUrlService('http://sho.rt/', $repository->reveal(), $urlGenerator->reveal());
        $ret = $service->shorten('http://long-url.com/pa/t/h');
        $this->assertInstanceOf(ShortUrl::class, $ret);
        $this->assertEquals('http://sho.rt/abcd89', $ret->getShortUrl());
        $this->assertEquals('http://long-url.com/pa/t/h', $ret->getLongUrl());
    }

    public function testConstructCreatesDefaultGenrator()
    {
        $repository = $this->prophesize(RepositoryInterface::class);
        $repository->findByLongUrl('http://long-url.com/pa/t/h')->willReturn(null);
        $repository->findByShortUrl(Argument::any())->willReturn(null);
        $repository->save(Argument::type(ShortUrl::class))->shouldBeCalled();
        $service = new ShortUrlService('http://sho.rt/', $repository->reveal());
        $ret = $service->shorten('http://long-url.com/pa/t/h');
        $this->assertInstanceOf(ShortUrl::class, $ret);
        // check if we generated 6 random chars, which means default generator is used
        $this->assertRegExp('#http://sho.rt/[a-z0-9]{6}#', $ret->getShortUrl());
        $this->assertEquals('http://long-url.com/pa/t/h', $ret->getLongUrl());
    }
}

