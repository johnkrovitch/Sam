<?php

namespace JK\Sam\Tests\File;

use JK\Sam\File\Locator;
use JK\Sam\File\Normalizer;
use JK\Sam\Tests\PHPUnitBase;

class LocatorTest extends PHPUnitBase
{
    public function testFindSingleFile()
    {
        $normalizer = new Normalizer($this->getCacheDir());
        $locator = new Locator($normalizer);

        // locate MUST find a single file
        $this->createFile('test.css');
        $sources = $locator->locate($this->getCacheDir().'/test.css');
        $this->assertCount(1, $sources);
        $this->assertEquals($this->getCacheDir().'/test.css', $sources[0]->getRealPath());
    }

    public function testFindMultipleFile()
    {
        $normalizer = new Normalizer($this->getCacheDir());
        $locator = new Locator($normalizer);

        // locator MUST find multiple files in a directory
        mkdir($this->getCacheDir().'/test');
        touch($this->getCacheDir().'/test/test.css');
        touch($this->getCacheDir().'/test/test2.css');
        $sources = $locator->locate($this->getCacheDir().'/test');
        $this->assertCount(2, $sources);
        
        $asserts = [
            $this->getCacheDir().'/test/test.css',
            $this->getCacheDir().'/test/test2.css',
        ];
        
        foreach ($sources as $source) {
            $this->assertContains($source->getRealPath(), $asserts);
        }
        $this->assertInstanceOf(Normalizer::class, $locator->getNormalizer());
    }

    public function testFinderPattern()
    {
        $normalizer = new Normalizer($this->getCacheDir());
        $locator = new Locator($normalizer);

        // finder pattern MUST return sources
        mkdir($this->getCacheDir().'/finder');
        touch($this->getCacheDir().'/finder/first.css');
        touch($this->getCacheDir().'/finder/second.css');
        touch($this->getCacheDir().'/finder/third.js');
        $sources = $locator->locate($this->getCacheDir().'/finder/*.css');

        $this->assertCount(2, $sources);
        $allowed = [
            $this->getCacheDir().'/finder/first.css',
            $this->getCacheDir().'/finder/second.css',
        ];
        $this->assertContains($sources[0]->getRealPath(), $allowed);
        $this->assertContains($sources[1]->getRealPath(), $allowed);
    }

    public function testFinderPatternWithEndingWildCard()
    {
        $normalizer = new Normalizer($this->getCacheDir());
        $locator = new Locator($normalizer);

        // finder pattern MUST return sources
        mkdir($this->getCacheDir().'/finder');
        touch($this->getCacheDir().'/finder/first.css');
        touch($this->getCacheDir().'/finder/second.css');
        touch($this->getCacheDir().'/finder/third.js');
        $sources = $locator->locate($this->getCacheDir().'/finder/*');

        $this->assertCount(3, $sources);
        $allowed = [
            $this->getCacheDir().'/finder/first.css',
            $this->getCacheDir().'/finder/second.css',
            $this->getCacheDir().'/finder/third.js',
        ];
        $this->assertContains($sources[0]->getRealPath(), $allowed);
        $this->assertContains($sources[1]->getRealPath(), $allowed);
        $this->assertContains($sources[2]->getRealPath(), $allowed);
    }
}
