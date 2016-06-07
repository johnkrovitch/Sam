<?php

namespace JK\Sam\File;

use Exception;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;

class Normalizer
{
    /**
     * Current application root absolute path, used to resolve relative path.
     *
     * @var string
     */
    protected $applicationPath;

    /**
     * Locator constructor.
     *
     * @param string $applicationPath
     */
    public function __construct($applicationPath = '')
    {
        $this->applicationPath = realpath($applicationPath);
    }

    /**
     * Normalize a source (string or SplInfo) into an instance of SplInfo.
     *
     * @param mixed $source
     * @return SplFileInfo
     * @throws Exception
     */
    public function normalize($source)
    {
        $fileSystem = new Filesystem();

        // if the source is a file info, it must exists and be readable
        if ($source instanceof SplFileInfo) {

            if (!$fileSystem->exists($source->getRealPath())) {
                throw new Exception('Unable to find '.$source.' during normalization process');
            }

            // the source is already normalized
            return $source;
        }

        // if the source is not an instance of SplInfo, it should be a string
        if (!is_string($source)) {
            throw new Exception(
                'The source should be a string if it is not an instance of SplInfo (instead of '.gettype($source).')'
            );
        }
        $path = $source;

        // if the file does not exists, try to add the application path before
        if (!$fileSystem->exists($source)) {

            if (!$fileSystem->exists($this->applicationPath.'/'.$source)) {
                throw new Exception(
                    'File '.$source.' not found, searched in '
                    .implode(', ', [$source, $this->applicationPath.'/'.$source])
                );
            }
            $path = $this->applicationPath.'/'.$source;
        }
        // normalize source using SplInfo
        $source = new SplFileInfo($path);

        return $source;
    }
}
