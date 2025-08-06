<?php

namespace Tests\Core\Twig;

use Core\Twig\TextExt;
use PHPUnit\Framework\TestCase;

class TextExtTest extends TestCase
{
    /**
     * @var textExt;
     *
     */
    private $textExt;

    public function setUp(): void
    {
        $this->textExt = new TextExt();
    }

    public function testExcerptWithShortText()
    {
        $text = "Salut";
        $this->assertEquals($text, $this->textExt->excerpt($text, 10));
    }

    public function testExcerptWithLongText()
    {
        $text = "Salut les gens";
        $this->assertEquals("Salut...", $this->textExt->excerpt($text, 7));
        $this->assertEquals("Salut les...", $this->textExt->excerpt($text, 12));
    }
}
