<?php

namespace JK\Sam\Filter\Compass;

use JK\Sam\Filter\Binary\BinaryFilterConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompassFilterConfiguration extends BinaryFilterConfiguration
{
    /**
     * Define allowed parameters and values for this configuration, using optionsResolver component.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            // We assume by default that the compass binary is loaded in $PATH
            ->setDefaults([
                'binary_path' => 'compass'
            ])
        ;
    }
}
