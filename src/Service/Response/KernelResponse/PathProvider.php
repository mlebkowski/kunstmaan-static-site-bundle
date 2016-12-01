<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Response\KernelResponse;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PathProvider
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var GeneratorRepository
     */
    private $repository;

    public function __construct(UrlGeneratorInterface $urlGenerator, GeneratorRepository $repository)
    {
        $this->urlGenerator = $urlGenerator;
        $this->repository = $repository;
    }

    public function getPaths($generatorName, $route, array $defaults = [])
    {
        $generator = $this->repository->offsetGet((string)$generatorName);

        foreach ($generator->getItems() as $params) {
            yield $this->urlGenerator->generate($route, $params + $defaults);
        }
    }
}
