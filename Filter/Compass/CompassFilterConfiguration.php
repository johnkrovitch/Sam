<?php

namespace JK\Sam\Filter\Compass;

use JK\Sam\Configuration\Configuration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompassFilterConfiguration extends Configuration
{
    /**
     * Define allowed parameters and values for this configuration, using optionsResolver component.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            // by default, we assume compass is loaded in $PATH
            ->setDefault('bin', 'compass')
        ;
    }
}
