<?php

namespace JK\Sam\Filter\Copy;

use Exception;
use JK\Sam\Filter\Filter;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;

class CopyFilter extends Filter
{
    /**
     * @param SplFileInfo[] $sources
     * @param SplFileInfo[] $destinations
     * @return SplFileInfo[]
     * @throws Exception
     */
    public function run(array $sources, array $destinations)
    {
        $fileSystem = new Filesystem();

        // must have the same sources and destinations number
        if (count($sources) !== count($destinations) && count($destinations) !== 1) {
            throw new Exception('Sources and destinations count mismatch');
        }

        if (count($destinations) === 1 && !is_dir($destinations[0]) && count($sources) > 1) {
            throw new Exception('If only one destination is set for multiple source, it should be a directory');
        }
        $copiedFiles = [];

        // copy generated files to its destination
        foreach ($sources as $index => $source) {

            if (array_key_exists($index, $destinations)) {
                $destination = $destinations[$index];
            } else {
                $destination = $destinations[0];
            }

            if (is_dir($destination)) {
                $destination = $destination.'/'.$source->getFilename();
            }
            $this->addNotification('copying "'.$source.'" to "'.$destination.'"');

            $fileSystem->copy($source, $destination);
            $copiedFiles[] = $destination;
        }

        return $copiedFiles;
    }

    /**
     * 
     */
    public function checkRequirements()
    {
    }

    /**
     * Return the file extensions supported by this filter.
     *
     * @return string[]
     */
    public function getSupportedExtensions()
    {
        return [
            '*'
        ];
    }
}
