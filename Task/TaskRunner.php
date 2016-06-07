<?php

namespace JK\Sam\Task;

use Exception;
use JK\Sam\File\Locator;
use JK\Sam\Filter\FilterInterface;
use SplFileInfo;

class TaskRunner
{
    /**
     * @var FilterInterface[]
     */
    protected $filters;

    /**
     * @var Locator
     */
    protected $locator;

    /**
     * TaskRunner constructor.
     *
     * @param array $filters
     * @param Locator $locator
     */
    public function __construct(array $filters, Locator $locator)
    {
        $this->filters = $filters;
        $this->locator = $locator;
    }

    /**
     * Run a task, load its sources before and call the clean method on the filter.
     *
     * @param Task $task
     * @throws Exception
     */
    public function run(Task $task)
    {
        // get configured filters for this task
        $filters = $task
            ->getConfiguration()
            ->getParameter('filters');

        // automatic adding of the copy filter
        $filters[] = 'copy';

        // get sources files
        $sources = $this->fetchSources($task);
        $destinations = $this->fetchDestinations($task);

        foreach ($filters as $filterName) {
            // get current configured filter
            $filter = $this->getFilter($filterName);

            // filter the files supported by this filter
            $filteredSources = $this->filterSources($sources, $filter);

            // apply the filter
            $updatedSources = $filter->run($filteredSources, $destinations);

            if ($updatedSources === null) {
                $updatedSources = [];
            }

            // clean the generated files by the filter
            $filter->clean();
            
            // update new sources
            $sources = $this->updateSources($sources, $filteredSources, $updatedSources);
        }
    }

    /**
     * Return a filter by its name. Throw an exception if it is not exists.
     *
     * @param string $name
     * @return FilterInterface
     * @throws Exception
     */
    protected function getFilter($name)
    {
        // filters must exists in configured filters
        if (!array_key_exists($name, $this->filters)) {
            throw new Exception('Invalid filter '.$name.'. Check your mapping configuration');
        }

        return $this->filters[$name];
    }

    /**
     * Fetch the source files from the task and return and array of SplInfo.
     *
     * @param Task $task
     * @return array
     */
    protected function fetchSources(Task $task)
    {
        $sources = [];

        foreach ($task->getSources() as $source) {
            // locate new resource and merge them to the existing sources
            $sources = array_merge($sources, $this->locator->locate($source));
        }

        return $sources;
    }

    /**
     * Fetch the destination files from the task and return and array of SplInfo.
     *
     * @param Task $task
     * @return array
     */
    protected function fetchDestinations(Task $task)
    {
        $sources = [];

        foreach ($task->getDestinations() as $source) {
            // locate new resource and merge them to the existing sources
            $sources[] = $this
                ->locator
                ->getNormalizer()
                ->normalize($source);
        }

        return $sources;
    }

    /**
     * Filter only the sources supported by the current filter.
     *
     * @param SplFileInfo[] $sources
     * @param FilterInterface $filter
     * @return array
     */
    protected function filterSources(array $sources, FilterInterface $filter)
    {
        $filteredSources = [];

        // if the filter support all the files types, no need to filter
        if (in_array('*', $filter->getSupportedExtensions())) {
            return $sources;
        }

        foreach ($sources as $source) {

            if (in_array($source->getExtension(), $filter->getSupportedExtensions())) {
                $filteredSources[] = $source;
            }
        }

        return $filteredSources;
    }

    /**
     * Remove the filtered files from the sources, and merge with the new ones.
     *
     * @param SplFileInfo[] $originalSources
     * @param SplFileInfo[] $filteredSources
     * @param SplFileInfo[] $updatedSources
     * @return SplFileInfo[]
     * @throws Exception
     */
    protected function updateSources(array $originalSources, array $filteredSources, array $updatedSources)
    {
        $sources = [];

        // keep only the not filtered files
        foreach ($originalSources as $source) {

            if (!in_array($source, $filteredSources)) {
                $sources[] = $source;
            }
        }
        // check updated files
        foreach ($updatedSources as $index => $source) {
            if (is_string($source)) {
                $updatedSources[$index] = new SplFileInfo($source);
            } else if (!($source instanceof SplFileInfo)) {
                throw new Exception('Invalid source file type '.gettype($source));
            }
        }
        // merge with the new sources
        $sources = array_merge($sources, $updatedSources);

        return $sources;
    }
}
