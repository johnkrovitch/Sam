<?php

namespace JK\Sam\Filter\Copy;

use Exception;
use JK\Sam\Filter\Filter;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;

class CopyFilter extends Filter
{
    /**
     * Copy the sources files to the destinations.
     *
     * @param SplFileInfo[] $sources
     * @param string[] $destinations
     *
     * @return SplFileInfo[]
     *
     * @throws Exception
     */
    public function run(array $sources, array $destinations)
    {
        $fileSystem = new Filesystem();
        
        // must have the same sources and destinations number
        if (count($sources) !== count($destinations) && count($destinations) !== 1) {
            throw new Exception('Sources and destinations count mismatch');
        }
        
        // if only one destination is specified for multiple sources, it should be a directory. We should create it if
        // it is not exists
        if (count($destinations) === 1 && !is_dir($destinations[0]) && count($sources) > 1) {
            $fileSystem->mkdir($destinations[0]);
        }
        $copiedFiles = [];
        
        // copy generated files to its destination
        foreach ($sources as $index => $source) {
            
            if (array_key_exists($index, $destinations)) {
                $destination = $destinations[$index];
            } else {
                $destination = $destinations[0];
            }
            
            if (is_dir($destination)) {
                $destination = $this->removeLastSlash($destination);
                
                if ($source instanceof \Symfony\Component\Finder\SplFileInfo && $source->getRelativePath()) {
                    $destination .= '/'.$source->getRelativePath();
                }
                $destination .= '/'.$source->getFilename();
            }
            $this->addNotification('copying "'.$source.'" to "'.$destination.'"');
            
            $fileSystem->copy($source->getRealPath(), $destination);
            $copiedFiles[] = $destination;
        }
        
        return $copiedFiles;
    }
    
    /**
     * No specific requirements for the copy filter.
     */
    public function checkRequirements()
    {
    }
    
    /**
     * Return the file extensions supported by this filter. All the files can be process by the copy filter.
     *
     * @return string[]
     */
    public function getSupportedExtensions()
    {
        return [
            '*'
        ];
    }
    
    /**
     * Remove the last slash of the string.
     *
     * @param string $string
     *
     * @return string
     */
    private function removeLastSlash($string)
    {
        if ('/' === substr($string, strlen($string) - 1)) {
            $string = substr($string, 0, strlen($string) - 1);
        }
        
        return $string;
    }
}
