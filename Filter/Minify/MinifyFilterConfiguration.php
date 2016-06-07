<?php

namespace JK\Sam\Filter\Minify;

use JK\Sam\Configuration\Configuration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MinifyFilterConfiguration extends Configuration
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
