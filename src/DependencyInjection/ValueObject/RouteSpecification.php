<?php

namespace Nassau\KunstmaanStaticSiteBundle\DependencyInjection\ValueObject;

class RouteSpecification
{
    private $route;

    private $generator;
    /**
     * @var array
     */
    private $defaults;

    /**
     * @param string $route
     * @param string $generator
     * @param array $defaults
     */
    public function __construct($route, $generator, array $defaults = [])
    {
        $this->route = $route;
        $this->generator = $generator;
        $this->defaults = $defaults;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

}
