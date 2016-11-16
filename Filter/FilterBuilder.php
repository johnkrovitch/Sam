<?php

namespace JK\Sam\Filter;

use Exception;
use JK\Sam\Configuration\ConfigurationInterface;
use JK\Sam\Filter\Compass\CompassFilter;
use JK\Sam\Filter\Copy\CopyFilter;
use JK\Sam\Filter\Merge\MergeFilter;
use JK\Sam\Filter\Minify\MinifyFilter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterBuilder
{
    /**
     * @var array
     */
    protected $mapping = [];

    /**
     * @var bool
     */
    protected $isDebug = true;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * FilterBuilder constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->mapping = [
            'compass' => CompassFilter::class,
            'copy' => CopyFilter::class,
            'merge' => MergeFilter::class,
            'minify' => MinifyFilter::class,
        ];
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Build the filter with the given configuration.
     *
     * @param array $filterConfigurations
     * @return FilterInterface[]
     * @throws Exception
     */
    public function build(array $filterConfigurations)
    {
        $resolver = new OptionsResolver();
        $filters = [];

        foreach ($filterConfigurations as $filterName => $filterConfiguration) {
            $resolver->clear();

            if ($filterConfiguration === null) {
                $filterConfiguration = [];
            }
            $configuration = $this->getFilterConfiguration($filterName);
            $configuration->configureOptions($resolver);
            $configuration->setParameters($resolver->resolve($filterConfiguration));

            $class = $this->mapping[$filterName];

            /** @var FilterInterface $filter */
            $filter = new $class(
                // filter's name
                $filterName,
                // filter's configuration
                $configuration,
                // event dispatcher
                $this->eventDispatcher
            );
            $filter->checkRequirements();

            $filters[$filterName] = $filter;
        }

        return $filters;
    }

    /**
     * Return filter configuration instance using the configured mapping.
     *
     * @param $filter
     * @return ConfigurationInterface
     * @throws Exception
     */
    protected function getFilterConfiguration($filter)
    {
        if (!array_key_exists($filter, $this->mapping)) {
            throw new Exception($filter.' filter not found in assets mapping. Check your configuration');
        }
        $class = $this->mapping[$filter].'Configuration';

        if (!class_exists($class)) {
            throw new Exception($class.' filter configuration class not found');
        }
        $configuration = new $class();

        if (!($configuration instanceof ConfigurationInterface)) {
            throw new Exception(get_class($configuration).' should be an instance of '.ConfigurationInterface::class);
        }

        return $configuration;
    }
}
