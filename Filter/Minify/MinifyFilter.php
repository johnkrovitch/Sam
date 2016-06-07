<?php

namespace JK\Sam\Filter\Minify;

use Exception;
use JK\Sam\Filter\Filter;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;
use SplFileInfo;

class MinifyFilter extends Filter
{
    /**
     * @param SplFileInfo[] $sources
     * @param SplFileInfo[] $destinations
     * @return SplFileInfo[]
     */
    public function run(array $sources, array $destinations)
    {
        $cssMinifier = new CSS();
        $jsMinifier = new JS();
        $hasJs = false;
        $hasCss = false;

        // add source files to the minifier
        foreach ($sources as $source) {

            if ($source->getExtension() == 'js') {
                $this->addNotification('Add '.$source.' to js minification');
                $jsMinifier->add($source);
                $hasJs = true;
            }

            if ($source->getExtension() == 'css') {
                $this->addNotification('Add '.$source.' to css minification');
                $cssMinifier->add($source);
                $hasCss = true;
            }
        }
        $cssMinified = $this->getCacheDir().'minified.css';
        $jsMinified = $this->getCacheDir().'minified.js';
        $updatedSources = [];

        // js minification if required
        if ($hasJs) {
            $this->addNotification($jsMinified.' js minification');
            $jsMinifier->minify($jsMinified);
            $updatedSources[] = $jsMinified;
        }

        // css minification if required
        if ($hasCss) {
            $this->addNotification($cssMinified.' css minification');
            $jsMinifier->minify($cssMinified);
            $updatedSources[] = $cssMinified;
        }

        return $updatedSources;
    }

    /**
     * @throws Exception
     */
    public function checkRequirements()
    {
        if (!class_exists('MatthiasMullie\Minify\CSS')) {
            throw new Exception(
                'MatthiasMullie\Minify\CSS should exists. '.
                'Install the MatthiasMullie\Minify library (composer require matthiasmullie/minify)'
            );
        }
    }

    /**
     * Return the file extensions supported by this filter.
     *
     * @return string[]
     */
    public function getSupportedExtensions()
    {
        return [
            'css',
            'js',
        ];
    }
}
