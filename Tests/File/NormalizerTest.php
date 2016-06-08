<?php

namespace JK\Sam\Tests\File;


use JK\Sam\File\Normalizer;
use JK\Sam\Tests\PHPUnitBase;
use SplFileInfo;

class NormalizerTest extends PHPUnitBase
{
    public function testNormalize()
    {
        $normalizer = new Normalizer($this->getCacheDir());

        // string normalization
        $file = $this->createFile('test.css');
        $normalizedFile = $normalizer->normalize($file);
        // it MUST return a instance of SplFileInfo representing the file
        $this->assertInstanceOf(SplFileInfo::class, $normalizedFile);
        $this->assertEquals($file, $normalizedFile->getRealPath());
        $this->assertExceptionThrown(function() use ($normalizer) {
            $normalizer->normalize('assets.missing.css');
        }, 'File assets.missing.css not found, searched in assets.missing.css, /tmp/jk-spam-assets/assets.missing.css');

        // SplFileInfo normalization
        $splFileInfo = $normalizer->normalize($normalizedFile);
        $this->assertEquals($splFileInfo->getRealPath(), $normalizedFile->getRealPath());
        $this->assertExceptionThrown(function() use ($normalizer) {
            $normalizer->normalize(new SplFileInfo('missing.css'));
        }, 'Unable to find missing.css during normalization process');

        // other type should fail
        $this->assertExceptionThrown(function() use ($normalizer) {
            $normalizer->normalize(42);
        }, 'The source should be a string if it is not an instance of SplInfo (instead of integer)');

        // application path completion
        mkdir($this->getCacheDir().'/test');
        touch($this->getCacheDir().'/test/test.css');
        $normalizedFile = $normalizer->normalize('test/test.css');
        $this->assertEquals($this->getCacheDir().'/test/test.css', $normalizedFile->getRealPath());
    }
}
