<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Response\StaticFiles;

use Nassau\KunstmaanStaticSiteBundle\DependencyInjection\ValueObject\FilesSpecification;
use Nassau\KunstmaanStaticSiteBundle\Service\Response\ResponseFactory;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesserInterface;
use Symfony\Component\HttpFoundation\Response;

class StaticFilesFactory implements ResponseFactory
{

    /**
     * @var FilesSpecification[]
     */
    private $files;

    /**
     * @var MimeTypeGuesserInterface
     */
    private $mimeTypeGuesser;

    public function __construct(\Traversable $files, MimeTypeGuesserInterface $mimeTypeGuesser)
    {
        $this->files = $files;
        $this->mimeTypeGuesser = $mimeTypeGuesser;
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
                    'content-type' => $this->mimeTypeGuesser->guess($file->getPathname())
                ]);
            }
        }
    }
}
