<?php

namespace JK\Sam\Tests\File;

require_once __DIR__.'/../PHPUnitBase.php';

use JK\Sam\File\Locator;
use JK\Sam\File\Normalizer;
use JK\Sam\Tests\PHPUnitBaseTest;

class LocatorTest extends PHPUnitBaseTest
{
    public function testLocate()
    {
        $normalizer = new Normalizer($this->getCacheDir());
        $locator = new Locator($normalizer);

        // locate SHOULD find a single file
        $this->createFile('test.css');
        $sources = $locator->locate($this->getCacheDir().'/test.css');
        $this->assertCount(1, $sources);
        $this->assertEquals($this->getCacheDir().'/test.css', $sources[0]->getRealPath());

        // locator SHOULD find multiple files in a directory
        mkdir($this->getCacheDir().'/test');
        touch($this->getCacheDir().'/test/test.css');
        touch($this->getCacheDir().'/test/test2.css');
        $sources = $locator->locate($this->getCacheDir().'/test');
        $this->assertCount(2, $sources);
        $this->assertEquals($this->getCacheDir().'/test/test.css', $sources[0]->getRealPath());
        $this->assertEquals($this->getCacheDir().'/test/test2.css', $sources[1]->getRealPath());

        $this->assertInstanceOf(Normalizer::class, $locator->getNormalizer());
    }
}
