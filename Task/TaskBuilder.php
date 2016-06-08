<?php

namespace JK\Sam\Task;


use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskBuilder
{
    /**
     * @var bool
     */
    protected $debug;

    /**
     * TaskBuilder constructor.
     * 
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;
    }

    /**
     * Build and return an array of Task.
     *
     * @param array $taskConfigurations
     * @return Task[]
     */
    public function build(array $taskConfigurations)
    {
        $resolver = new OptionsResolver();
        $tasks = [];
        
        foreach ($taskConfigurations as $taskName => $taskConfiguration) {
            $resolver->clear();

            // debug mode
            if ($this->debug === true) {
                $taskConfiguration['debug'] = $this->debug;
            }
            // add copy filter in last position if required
            if (!$this->hasCopyFilter($taskConfiguration)) {
                $taskConfiguration['filters'][] = 'copy';
            }

            $configuration = new TaskConfiguration();
            $configuration->configureOptions($resolver);
            $configuration->setParameters($resolver->resolve($taskConfiguration));

            $task = new Task($taskName, $configuration);
            $tasks[$taskName] = $task;
        }

        return $tasks;
    }

    /**
     * Return true if tje copy filter is in last position.
     *
     * @param array $configuration
     * @return bool
     */
    protected function hasCopyFilter(array $configuration)
    {
        return is_array($configuration['filters'])
            && $configuration['filters'][count($configuration['filters'] - 1)] == 'copy'
        ;
    }
}
