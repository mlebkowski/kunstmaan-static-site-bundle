<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Dumper;

use Symfony\Component\HttpFoundation\Response;

class StaticItem
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $location;

    /**
     * @param Response $response
     * @param string $path
     * @param string $location
     */
    public function __construct(Response $response, $path, $location)
    {
        $this->response = $response;
        $this->path = $path;
        $this->location = $location;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

}
