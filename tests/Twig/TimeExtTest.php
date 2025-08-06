<?php

namespace Tests\Core\Twig;

use Core\Twig\TimeExt;
use PHPUnit\Framework\TestCase;

class TimeExtTest extends TestCase
{
    /**
     * @var timeExt;
     *
     */
    private $timeExt;

    public function setUp(): void
    {
        $this->timeExt = new TimeExt();
    }


    public function testDateFormat()
    {
        $date = new \DateTime();
        $format = 'd/m/Y H:i';
        $result = '<span class="timeago" datetime="'.$date->format(\DateTime::ATOM).'">'.$date->format($format).'</span>';
        $this->assertEquals($result, $this->timeExt->ago($date));
    }
}
