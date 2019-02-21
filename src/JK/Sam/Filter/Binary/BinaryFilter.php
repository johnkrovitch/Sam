<?php

namespace JK\Sam\Filter\Binary;

use JK\Sam\Filter\Filter;
use SplFileInfo;

class BinaryFilter extends Filter
{
    /**
     * Apply a filter to a list of source files
     *
     * @param SplFileInfo[] $files
     * @param SplFileInfo[] $destinations
     *
     * @return SplFileInfo[]
     */
    public function run(array $files, array $destinations)
    {
        // TODO: Implement run() method.
    }

    /**
     * Return the file extensions supported by this filter.
     *
     * @return string[]
     */
    public function getSupportedExtensions()
    {
        // TODO: Implement getSupportedExtensions() method.
    }
}