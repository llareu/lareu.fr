<?php

namespace Tests\Core\Twig;

use Core\Twig\FormExt;
use PHPUnit\Framework\TestCase;

class FormExtTest extends TestCase
{

    /**
     * @var FormExt
     */
    // private $formExt;

    // public function setUp(): void
    // {
    //     $this->formExt = new FormExt();
    // }

    // private function trim(string $string)
    // {
    //     $lines = explode(PHP_EOL, $string);
    //     $lines = array_map('trim', $lines);
    //     return implode('', $lines);
    // }

    // public function assertSimilar(string $expected, string $actual)
    // {
    //     $this->assertEquals($this->trim($expected), $this->trim($actual));
    // }

    // public function testField()
    // {
    //     $html = $this->formExt->field([], 'name', 'demo', 'Titre', ['class' => 'demo']);

    //     $this->assertSimilar("
    //         <div class=\"form-group\">
    //             <label for=\"name\">Titre</label>
    //             <input type=\"text\" class=\"form-control demo\" name=\"name\" id=\"name\" value=\"demo\" />
    //         </div>
    //     ", $html);
    // }

    // public function testFieldWithClass()
    // {
    //     $html = $this->formExt->field([], 'name', 'demo', 'Titre');

    //     $this->assertSimilar("
    //         <div class=\"form-group\">
    //             <label for=\"name\">Titre</label>
    //             <input type=\"text\" class=\"form-control\" name=\"name\" id=\"name\" value=\"demo\" />
    //         </div>
    //     ", $html);
    // }

    // public function testTextarea()
    // {
    //     $html = $this->formExt->field([], 'name', 'demo', 'Titre', ['type' => 'textarea']);

    //     $this->assertSimilar("
    //         <div class=\"form-group\">
    //             <label for=\"name\">Titre</label>
    //             <textarea class=\"form-control\" name=\"name\" id=\"name\">demo</textarea>
    //         </div>
    //     ", $html);
    // }

    // public function testFieldWithErrors()
    // {
    //     $context = ['errors' => ['name' => 'erreur']];
    //     $html = $this->formExt->field($context, 'name', 'demo', 'Titre');

    //     $this->assertSimilar("
    //         <div class=\"form-group has-danger\">
    //             <label for=\"name\">Titre</label>
    //             <input type=\"text\" class=\"form-control form-control-danger\" name=\"name\" id=\"name\" value=\"demo\" />
    //             <small class=\"form-text text-muted\">erreur</small>
    //         </div>
    //     ", $html);
    // }

    // public function testSelect()
    // {
    //     $html = $this->formExt->field(
    //         [],
    //         'name',
    //         2,
    //         'Titre',
    //         ['options' => [1 => 'demo1', 2 => 'demo2']]
    //     );

    //     $this->assertSimilar('
    //         <div class="form-group">
    //             <label for="name">Titre</label>
    //             <select class="form-control" name="name" id="name">
    //                 <option value="1">demo1</option>
    //                 <option value="2" selected>demo2</option>
    //             </select>
    //         </div>', $html);
    // }
}
