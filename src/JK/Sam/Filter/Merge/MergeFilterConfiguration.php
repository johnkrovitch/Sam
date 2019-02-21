<?php

namespace JK\Sam\Filter\Merge;

use JK\Sam\Configuration\Configuration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MergeFilterConfiguration extends Configuration
{
    /**
     * Define allowed parameters and values for this configuration, using optionsResolver component.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
