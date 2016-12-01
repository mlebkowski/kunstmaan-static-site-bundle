<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Dumper;

use Nassau\KunstmaanStaticSiteBundle\Service\Response\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;

class StaticSiteDumper
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }


    /**
     * @return \Generator|Response[]
     */
    public function getStaticSite()
    {
        foreach ($this->responseFactory->getResponses() as $key => $response) {
            yield $key => $response;
        }
    }
}
