<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Generator;

use Kunstmaan\NodeBundle\Entity\NodeTranslation;
use Kunstmaan\NodeBundle\Repository\NodeTranslationRepository;

class NodesGenerator implements RouteParametersGenerator
{

    /**
     * @var NodeTranslationRepository
     */
    private $repository;

    /**
     * @param NodeTranslationRepository $repository
     */
    public function __construct(NodeTranslationRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @return array[]
     */
    public function getItems()
    {
        $iterator = $this->repository->createQueryBuilder('nt')
            ->join('nt.node', 'n')
            ->where('nt.online = 1')
            ->andWhere('n.deleted = 0')
            ->getQuery()->iterate();


        /** @var NodeTranslation $nodeTranslation */
        foreach ($iterator as list ($nodeTranslation)) {
            yield [
                'url' => (string)$nodeTranslation->getUrl(),
                '_locale' => $nodeTranslation->getLang(),
            ];
        }
    }
}
