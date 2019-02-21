<?php

namespace JK\Sam\Filter\Binary;

use JK\Configuration\Configuration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BinaryFilterConfiguration extends Configuration
{
    /**
     * Define allowed parameters and values for this configuration, using optionsResolver component.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired([
                'binary_path',
            ])
        ;
    }
}