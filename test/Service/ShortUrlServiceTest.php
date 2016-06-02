<?php

namespace MiniUrl\Test\Service;

use MiniUrl\Entity\ShortUrl;
use MiniUrl\Exception\InvalidArgumentException;
use MiniUrl\Repository\RepositoryInterface;
use MiniUrl\Service\ShortUrlService;
use MiniUrl\Service\UrlGeneratorInterface;
use PHPUnit_Framework_TestCase;
use Prophecy\Argument;

class ShortUrlServiceTest extends PHPUnit_Framework_TestCase
{
    public function testShortenThrowsExceptionIfUrlIsNotValid()
    {
        $repository = $this->prophesize(RepositoryInterface::class);
        $service = new ShortUrlService($repository->reveal());
        $this->setExpectedException(InvalidArgumentException::class);
        $service->shorten('//not-valid-url');
    }

    public function testShortenReturnsPreviouslyShortenedUrl()
    {
        $repository = $this->prophesize(RepositoryInterface::class);
        $repository->findShortHash('http://long-url.com/pa/t/h')->willReturn('abcdef');
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->generate()->shouldNotBeCalled();
        $service = new ShortUrlService($repository->reveal(), $urlGenerator->reveal());
        $ret = $service->shorten('http://long-url.com/pa/t/h');
        $this->assertSame('abcdef', $ret);
    }

    public function testShortenShortensUrl()
    {
        $repository = $this->prophesize(RepositoryInterface::class);
        $repository->findShortHash('http://long-url.com/pa/t/h')->willReturn(null);
        $repository->findLongUrl('abcd89')->willReturn(null);
        $repository->save('abcd89', 'http://long-url.com/pa/t/h')->shouldBeCalled();
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->generate()->willReturn('abcd89');
        $service = new ShortUrlService($repository->reveal(), $urlGenerator->reveal());
        $ret = $service->shorten('http://long-url.com/pa/t/h');
        $this->assertEquals('abcd89', $ret);
    }

    public function testConstructCreatesDefaultGenerator()
    {
        $repository = $this->prophesize(RepositoryInterface::class);
        $repository->findShortHash('http://long-url.com/pa/t/h')->willReturn(null);
        $repository->findLongUrl(Argument::any())->willReturn(null);
        $repository->save(Argument::type('string'), 'http://long-url.com/pa/t/h')->shouldBeCalled();
        $service = new ShortUrlService($repository->reveal());
        $ret = $service->shorten('http://long-url.com/pa/t/h');
        // check if we generated 6 random chars, which means default generator is used
        $this->assertRegExp('#[A-Za-z0-9]{6}#', $ret);
    }
}

