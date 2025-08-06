<?php

namespace Tests\Core\News\Actions;

use Core\Router;
use PHPUnit\Framework\TestCase;
use Root\News\Actions\NewsShowAction;
use Root\News\Table\NewTable;
use GuzzleHttp\Psr7\ServerRequest;
use Core\Renderer\RendererInterface;
use Prophecy\PhpUnit\ProphecyTrait;
use Root\News\Entity\NewsEntity;

class NewsShowActionTest extends TestCase
{
    
    private $action;
    
    private $renderer;
    
    private $newTable;
    
    private $router;

    use ProphecyTrait;

    public function setUp(): void
    {
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->newTable = $this->prophesize(NewTable::class);

        $this->router = $this->prophesize(Router::class);

        $this->action = new NewsShowAction(
            $this->renderer->reveal(),
            $this->router->reveal(),
            $this->newTable->reveal()
        );
    }

    public function makeNew(int $id, string $slug): NewsEntity
    {
        $new = new NewsEntity();
        $new->id = $id;
        $new->slug = $slug;
        return $new;
    }

    public function testShowRedirect()
    {
        $new = $this->makeNew(9, 'azeaze-azeaze');
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', $new->id)
            ->withAttribute('slug', 'demo');

        $this->router->generateUri('news.show', ['id' => $new->id, 'slug' => $new->slug])->willReturn('/demo2');
        $this->newTable->findWithCategory($new->id)->willReturn($new);
        $response = call_user_func_array($this->action, [$request]);

        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals(['/demo2'], $response->getHeader('location'));
    }

    public function testShowRender()
    {
        $new = $this->makeNew(9, 'azeaze-azeaze');
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', $new->id)
            ->withAttribute('slug', $new->slug);

        $this->newTable->findWithCategory($new->id)->willReturn($new);
        $this->renderer->render('@news/show', ['new' => $new])->willReturn('');
        $response = call_user_func_array($this->action, [$request]);

        $this->assertEquals('', $response);
    }
}
