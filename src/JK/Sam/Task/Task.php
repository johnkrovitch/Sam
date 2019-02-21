<?php

namespace JK\Sam\Task;

use JK\Sam\Configuration\ConfigurationInterface;

/**
 * Represent a task to execute with a specific filter on sources files to destination file.
 */
class Task
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * Task constructor.
     * 
     * @param $name
     * @param TaskConfiguration $configuration
     */
    public function __construct($name, TaskConfiguration $configuration)
    {
        $this->name = $name;
        $this->configuration = $configuration;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ConfigurationInterface
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Return the configured sources.
     *
     * @return string[]
     */
    public function getSources()
    {
        return $this
            ->configuration
            ->getParameter('sources');
    }

    /**
     * Define the task new sources.
     *
     * @param array $sources
     */
    public function setSources(array $sources)
    {
        $parameters = $this
            ->configuration
            ->getParameters();
        $parameters['sources'] = $sources;

        $this
            ->configuration
            ->setParameters($parameters);
    }

    /**
     * Return the configured destinations.
     *
     * @return string[]
     */
    public function getDestinations()
    {
        return $this
            ->configuration
            ->getParameter('destinations');
    }

    /**
     * Return true if the task is in debug mode.
     *
     * @return boolean
     */
    public function isDebug()
    {
        return $this
            ->configuration
            ->getParameter('debug');
    }
}
