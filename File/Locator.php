<?php

namespace JK\Sam\File;

use Exception;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class Locator
{
    /**
     * @var Normalizer
     */
    protected $normalizer;

    /**
     * Locator constructor.
     *
     * @param Normalizer $normalizer
     */
    public function __construct(Normalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * Locate a source either if it a file or a directory and normalize it. Return an array of SplFileInfo.
     *
     * @param mixed $source
     * @return SplFileInfo[]
     * @throws Exception
     */
    public function locate($source)
    {
        $sources = [];
        $normalizedSources = [];

        // if the wildcard is at the end of the string, it means that we look for a directory
        $endingWithWilCard = substr($source, strlen($source) - 2) === '/*';

        if ($endingWithWilCard) {
            $source = substr($source, 0, strlen($source) - 2);
        }

        if (is_dir($source) || $endingWithWilCard) {
            $finder = new Finder();
            $finder->files()->in($source);

            foreach ($finder as $file) {
                $sources[] = $file;
            }
        } else if (strstr($source, '*') !== false) {
            // if the source contains a wildcard, we use it with the finder component
            $sources = $this->getSourcesFromFinder($source);
        } else {
            $sources[] = $source;
        }

        // each found sources should be normalized
        foreach ($sources as $source) {
            $normalizedSources[] = $this
                ->normalizer
                ->normalize($source);
        }

        return $normalizedSources;
    }

    /**
     * @return Normalizer
     */
    public function getNormalizer()
    {
        return $this->normalizer;
    }

    /**
     * @param $source
     * @return SplFileInfo[]
     */
    protected function getSourcesFromFinder($source)
    {
        $array = explode(DIRECTORY_SEPARATOR, $source);
        $filename = array_pop($array);
        $directory = $source;
        $pattern = '*';

        // if a dot is present, the last part is the filename pattern
        if (strstr($filename, '.') !== false) {
            $pattern = $filename;
            $directory = implode('/', $array);
        }
        $finder = new Finder();
        $finder
            ->name($pattern)
            ->in($directory);

        $sources = [];

        foreach ($finder as $source) {
            $sources[] = $source;
        }

        return $sources;
    }
}
