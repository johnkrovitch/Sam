<?php

namespace JK\Sam\Filter;

use JK\Sam\Configuration\ConfigurationInterface;
use JK\Sam\Event\NotificationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use SplFileInfo;

abstract class Filter implements FilterInterface
{
    /**
     * Filter's name
     * 
     * @var string
     */
    protected $name;

    /**
     * Filter's configuration
     * 
     * @var ConfigurationInterface
     */
    protected $configuration;
    
    /**
     * Notification dispatcher
     * 
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Apply a filter to a list of source files
     *
     * @param SplFileInfo[] $files
     * @param SplFileInfo[] $destinations
     * @return SplFileInfo[]
     */
    abstract public function run(array $files, array $destinations);

    /**
     * Filter constructor.
     *
     * @param string $name
     * @param ConfigurationInterface $configuration
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        $name,
        ConfigurationInterface $configuration,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->name = $name;
        $this->configuration = $configuration;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Use this method to check your filter requirements (for example, if the library required by this filter are
     * installed).
     */
    public function checkRequirements()
    {        
    }

    /**
     * Remove the assets cache directory.
     */
    public function clean()
    {
        $fileSystem = new Filesystem();

        if ($fileSystem->exists($this->getCacheDir())) {
            $fileSystem->remove($this->getCacheDir());
        }
    }

    /**
     * Return the assets cache dir.
     *
     * @return string
     */
    public function getCacheDir()
    {
        $fileSystem = new Filesystem();
        $path = 'var/cache/assets/'.$this->name.'/';

        if (!$fileSystem->exists($path)) {
            $fileSystem->mkdir($path);
        }

        return $path;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return files matching $pattern in filters cache directory.
     *
     * @param string $pattern
     * @return Finder|SplFileInfo[]
     */
    protected function findFilesInCacheDir($pattern)
    {
        $finder = new Finder();

        return $finder
            ->name($pattern)
            ->in($this->getCacheDir());
    }

    /**
     * Add a notification to the subscriber.
     *
     * @param $message
     */
    protected function addNotification($message)
    {
        $event = new NotificationEvent();
        $event->setMessage($message);

        $this
            ->eventDispatcher
            ->dispatch(NotificationEvent::NAME, $event);
    }
}
