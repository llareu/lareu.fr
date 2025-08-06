<?php

namespace Tests\Core;

use Core\Renderer\PHPRenderer;
use PHPUnit\Framework\TestCase;

class PHPRendererTest extends TestCase
{
    private $renderer;

    public function setUp(): void
    {
        $this->renderer = new PHPRenderer(__DIR__.DIRECTORY_SEPARATOR.'views');
    }

    public function testRenderTheRightPath()
    {
        $this->renderer->addPath('news', __DIR__.DIRECTORY_SEPARATOR.'views');
        $content = $this->renderer->render('@news/demo');
        $this->assertEquals('View Test', $content);
    }

    public function testRenderTheDefaultPath()
    {
        $content = $this->renderer->render('demo');
        $this->assertEquals('View Test', $content);
    }

    public function testRenderWithParams()
    {
        $content = $this->renderer->render('demoParams', ['nom' => 'Ginette']);
        $this->assertEquals('Salut Ginette', $content);
    }

    public function testGlobalParams()
    {
        $this->renderer->addGlobal('nom', 'Ginette');
        $content = $this->renderer->render('demoParams');
        $this->assertEquals('Salut Ginette', $content);
    }
}
