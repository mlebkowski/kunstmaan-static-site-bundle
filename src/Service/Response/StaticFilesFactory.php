<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Response;

use Nassau\KunstmaanStaticSiteBundle\DependencyInjection\ValueObject\FilesSpecification;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpFoundation\Response;

class StaticFilesFactory implements ResponseFactory
{

    /**
     * @var FilesSpecification[]
     */
    private $files;

    public function __construct(\Traversable $files)
    {
        $this->files = $files;
    }


    /**
     * @return Response[]
     */
    public function getResponses()
    {
        foreach ($this->files as $criteria) {

            $finder = (new Finder)->in($criteria->getDirectory());

            foreach ($criteria->getInclude() as $name) {
                $finder->name($name);
            }

            foreach ($criteria->getExclude() as $name) {
                $finder->notName($name);
            }

            /** @var SplFileInfo $file */
            foreach ($finder->files() as $file) {
                $relativePath = $criteria->getTargetPath() . $file->getRelativePathname();

                yield $relativePath => new Response($file->getContents(), Response::HTTP_OK, [
                    'content-type' => MimeTypeGuesser::getInstance()->guess($file->getPathname())
                ]);
            }
        }
    }
}
