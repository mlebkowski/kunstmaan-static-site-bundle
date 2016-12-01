<?php

namespace Nassau\KunstmaanStaticSiteBundle\DependencyInjection\ValueObject;

class FilesSpecification
{
    private $directory;

    private $targetPath = "/";

    private $include = [];

    private $exclude = [];

    /**
     * @param $directory
     * @param string $targetPath
     * @param array $include
     * @param array $exclude
     */
    public function __construct($directory, $targetPath = '/', array $include = [], array $exclude = ['*.php'])
    {
        $this->directory = $directory;
        $this->targetPath = $targetPath;
        $this->include = $include;
        $this->exclude = $exclude;
    }

    /**
     * @return mixed
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @return string
     */
    public function getTargetPath()
    {
        return $this->targetPath;
    }

    /**
     * @return array
     */
    public function getInclude()
    {
        return $this->include;
    }

    /**
     * @return array
     */
    public function getExclude()
    {
        return $this->exclude;
    }

}
