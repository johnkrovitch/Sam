<?php

namespace JK\Sam\Filter\Compass;

use Exception;
use JK\Sam\Filter\Filter;
use SplFileInfo;
use Symfony\Component\Process\Process;

class CompassFilter extends Filter
{
    /**
     * Compile scss file into css file via compass.
     *
     * @param SplFileInfo[] $sources
     * @param SplFileInfo[] $destinations
     * @return SplFileInfo[]
     *
     * @throws Exception
     */
    public function run(array $sources, array $destinations)
    {
        $updatedSources = [];

        foreach ($sources as $source) {
            // remove .scss extension and add .css extension
            $css = $this->getCacheDir().$source->getBasename('.scss').'.css';
            $this->addNotification('compiling '.$source.' to '.$css);

            // compilation start
            $process = new Process($command = $this->buildCommandString($source));
            $process->run();

            if (!$process->isSuccessful()) {
                throw new Exception(
                    'An error has occurred during the compass compile task : "'.$process->getErrorOutput().""
                    .' (running "'.$command.'")'
                );
            }

            // add css file to destination files
            $updatedSources[] = $css;
        }
        if (count($updatedSources)) {
            // compilation success notification
            $this->addNotification('scss files compilation success');
        }

        return $updatedSources;
    }

    /**
     * Check that compass is installed and available.
     * 
     * @throws Exception
     */
    public function checkRequirements()
    {
        $process = new Process($this->getBin().' version');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new Exception('compass is not found at '.$this->getBin().'. '.$process->getErrorOutput());
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
            'scss'
        ];
    }

    /**
     * Get the binary path.
     *
     * @return string
     */
    protected function getBin()
    {
        return $this
            ->configuration
            ->getParameter('bin');
    }

    /**
     * Build the compass compile command line.
     *
     * @param SplFileInfo $source
     * @return string
     */
    protected function buildCommandString($source)
    {
        $commandPattern = '%s %s %s %s %s';
        $command = sprintf(
        // pattern
            $commandPattern,
            // compass binary path
            $this->getBin(),
            // compass command
            'compile',
            // sources
            $source->getRealPath(),
            // destination file
            '--css-dir='.$this->getCacheDir(),
            // sass directory
            '--sass-dir='.$source->getPath()
        );

        return $command;
    }
}
