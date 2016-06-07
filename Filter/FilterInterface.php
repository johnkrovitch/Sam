<?php

namespace JK\Sam\Filter;

use JK\Sam\Configuration\ConfigurationInterface;
use SplFileInfo;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface FilterInterface
{
    /**
     * FilterInterface constructor.
     *
     * @param $name
     * @param ConfigurationInterface $configuration
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        $name,
        ConfigurationInterface $configuration,
        EventDispatcherInterface $eventDispatcher
    );

    /**
     * Apply a filter to a list of source files and return the updated files.
     *
     * @param SplFileInfo[] $files
     * @param SplFileInfo[] $destinations
     * @return SplFileInfo[]
     */
    public function run(array $files, array $destinations);

    /**
     * Return the file extensions supported by this filter.
     *
     * @return string[]
     */
    public function getSupportedExtensions();

    /**
     * Clean the generated files.
     */
    public function clean();
}
