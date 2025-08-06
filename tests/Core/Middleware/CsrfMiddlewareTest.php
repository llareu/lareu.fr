<?php

namespace Tests\Core\Middleware;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;
use Core\Middleware\CsrfMiddleware;
use Core\Exception\CsrfInvalidException;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfMiddlewareTest extends TestCase
{
    /**
     * @var CsrfMiddleware
     */
    private $middleware;

    /**
     * @var array
     */
    private $session;

    public function setUp(): void
    {
        $this->session = [];
        $this->middleware = new CsrfMiddleware($this->session);
    }

    public function testLetGetRequestPass()
    {
        $handler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $handler->expects($this->once())
            ->method('handle');

        $request = (new ServerRequest('GET', '/demo'));
        $this->middleware->process($request, $handler);
    }

    public function testBlockPostRequestWithoutCsrf()
    {
        $handler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $handler->expects($this->never())->method('handle');

        $request = (new ServerRequest('POST', '/demo'));
        $this->expectException(CsrfInvalidException::class);
        $this->middleware->process($request, $handler);
    }

    public function testLetPostWithTokenPass()
    {
        $handler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $handler->expects($this->once())->method('handle');

        $request = (new ServerRequest('POST', '/demo'));
        $token = $this->middleware->generateToken();
        $request = $request->withParsedBody(['_csrf' => $token]);
        $this->middleware->process($request, $handler);
    }

    public function testBlockPostRequestWithInvalidCsrf()
    {
        $handler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $handler->expects($this->never())->method('handle');

        $request = (new ServerRequest('POST', '/demo'));
        $this->middleware->generateToken();

        $request = $request->withParsedBody(['_csrf' => 'demo']);
        $this->expectException(CsrfInvalidException::class);
        $this->middleware->process($request, $handler);
    }

    public function testLetPostWithTokenPassOnce()
    {
        $handler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $handler->expects($this->once())->method('handle');

        $request = (new ServerRequest('POST', '/demo'));
        $token = $this->middleware->generateToken();
        $request = $request->withParsedBody(['_csrf' => $token]);
        $this->middleware->process($request, $handler);
        $this->expectException(CsrfInvalidException::class);
        $this->middleware->process($request, $handler);
    }

        public function testLimitTheTokenNumber()
    {
        for($i=0;$i<100;$i++) {
            $token = $this->middleware->generateToken();
        }
        $this->assertCount(10, $this->session['csrf'] ?? []);
        $this->assertEquals($token, $this->session['csrf'][9]);
    }
}
