<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Response;

class ChainResponseFactory implements ResponseFactory
{

    /**
     * @var ResponseFactory[]
     */
    private $storage = [];

    public function addFactory($name, ResponseFactory $factory)
    {
        $this->storage[$name] = $factory;
    }

    public function getResponses()
    {
        foreach ($this->storage as $factory) {
            foreach ($factory->getResponses() as $key => $response) {
                yield $key => $response;
            }
        }
    }

}
