<?php

namespace JK\Sam\Filter\Merge;

use JK\Sam\Filter\Filter;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Merge different source files into one.
 */
class MergeFilter extends Filter
{
    /**
     * @param SplFileInfo[] $sources
     * @param SplFileInfo[] $destinations
     * @return array|SplFileInfo[]
     */
    public function run(array $sources, array $destinations)
    {
        $fileSystem = new Filesystem();
        $mergedFiles = [];
        
        foreach ($this->getSupportedExtensions() as $extension) {
            $shouldAddMergeFile = false;
            $mergedFile = $this->getCacheDir().'merged.'.$extension;
            
            // creating the merge file if not exists
            if (!$fileSystem->exists($mergedFile)) {
                $this->addNotification('creating '.$mergedFile.' merged file');
                $fileSystem->touch($mergedFile);
            }
            // reset the merge file
            $fileSystem->dumpFile($mergedFile, '');
            
            foreach ($sources as $source) {
                
                if ($source->getExtension() !== $extension) {
                    continue;
                }
                
                $this->addNotification('merging file '.$source);
                
                // append the current content to the merge file
                $content = file_get_contents($source);
                $fileSystem->dumpFile($mergedFile, $content);
                
                $shouldAddMergeFile = true;
            }
            
            if ($shouldAddMergeFile) {
                $mergedFiles[] = $mergedFile;
            }
        }
        
        return $mergedFiles;
    }
    
    /**
     * Return the file extensions supported by this filter
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
