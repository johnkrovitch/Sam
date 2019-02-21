<?php

namespace JK\Sam\Tests;

use Exception;
use JK\Sam\File\Locator;
use JK\Sam\File\Normalizer;
use JK\Sam\Task\Task;
use JK\Sam\Task\TaskConfiguration;
use JK\Sam\Task\TaskRunner;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PHPUnitBase extends PHPUnit_Framework_TestCase
{
    protected $fileSystem;

    /**
     * PHPUnitBase constructor.
     *
     * @param null|string $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->fileSystem = new Filesystem();
    }

    /**
     * @return string
     */
    protected function getCacheDir()
    {
        $cacheDir = sys_get_temp_dir().'/jk-spam-assets';

        if (!$this->fileSystem->exists($cacheDir)) {
            $this
                ->fileSystem
                ->mkdir($cacheDir);
        }

        return $cacheDir;
    }

    /**
     *
     */
    protected function removeCacheDir()
    {
        $this
            ->fileSystem
            ->remove($this->getCacheDir());
    }

    /**
     * @param $pathInCacheDir
     * @return string
     */
    protected function createFile($pathInCacheDir)
    {
        $this
            ->fileSystem
            ->touch($this->getCacheDir().'/'.$pathInCacheDir);

        return $this->getCacheDir().'/'.$pathInCacheDir;
    }

    /**
     * @param $callable
     * @param null $message
     */
    protected function assertExceptionThrown($callable, $message = null)
    {
        $exceptionThrown = false;

        try {
            $callable();
        } catch (Exception $e) {
            $exceptionThrown = true;

            if (null !== $message) {
                $this->assertEquals($message, $e->getMessage());
            }
        }
        $this->assertEquals(true, $exceptionThrown);
    }

    /**
     * Remove the cache directory.
     */
    protected function tearDown()
    {
        $this->removeCacheDir();
    }

    /**
     * @param array $filters
     * @return TaskRunner
     */
    protected function getTaskRunner($filters = [])
    {
        $locator = new Locator(new Normalizer($this->getCacheDir()));
        $taskRunner = new TaskRunner($filters, $locator);

        return $taskRunner;
    }

    /**
     * @param $name
     * @param $configuration
     * @return Task
     */
    protected function getTask($name, $configuration)
    {
        $resolver = new OptionsResolver();
        $taskConfiguration = new TaskConfiguration();
        $taskConfiguration->configureOptions($resolver);
        $taskConfiguration->setParameters($resolver->resolve($configuration));
        $task = new Task($name, $taskConfiguration);

        return $task;
    }
}
