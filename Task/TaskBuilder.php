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

            if ($this->debug === true) {
                $taskConfiguration['debug'] = $this->debug;
            }
            $configuration = new TaskConfiguration();
            $configuration->configureOptions($resolver);
            $configuration->setParameters($resolver->resolve($taskConfiguration));


            $task = new Task($taskName, $configuration);
            $tasks[$taskName] = $task;
        }

        return $tasks;
    }
}
