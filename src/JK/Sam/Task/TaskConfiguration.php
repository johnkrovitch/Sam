<?php

namespace JK\Sam\Task;

use JK\Sam\Configuration\Configuration;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Configured Tasks options.
 */
class TaskConfiguration extends Configuration
{
    /**
     * Define allowed parameters and values for this configuration, using optionsResolver component.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            // filter option is required
            ->setRequired('filters')
            ->setAllowedTypes('filters', 'array')

            // sources is required and should be an array
            ->setRequired('sources')
            ->setAllowedTypes('sources', 'array')

            // destination should be a string, no array is allowed for now
            ->setRequired('destinations')
            ->setAllowedTypes('destinations', 'array')

            // debug mode (allow more verbosity)
            ->setDefault('debug', false)
            ->setAllowedTypes('debug', 'boolean')
        ;
    }
}
