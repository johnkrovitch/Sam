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
                $this->addNotification('add '.$source.' to css minification');
                $cssMinifier->add($source);
                $hasCss = true;
            }
        }
        $cssMinPath = $this->getCacheDir().'minified.css';
        $jsMinPath = $this->getCacheDir().'minified.js';
        $updatedSources = [];

        // js minification if required
        if ($hasJs) {
            $this->addNotification($jsMinPath.' js minification');
            $jsMinifier->minify($jsMinPath);
            $updatedSources[] = $jsMinPath;
        }

        // css minification if required
        if ($hasCss) {
            $this->addNotification($cssMinPath.' css minification');
            // avoid css rewriting by not passing an argument to the minify() method and dump the content into the file
            // ourselves
            $content = $cssMinifier->minify();
            file_put_contents($cssMinPath, $content);
            
            $updatedSources[] = $cssMinPath;
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
