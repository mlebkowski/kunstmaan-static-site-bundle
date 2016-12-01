<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Generator;

class StaticGenerator implements RouteParametersGenerator
{
    private $items = [];

    /**
     * @param array $items
     */
    public function __construct(array $items = [[]])
    {
        $this->items = $items;
    }


    /**
     * @return array[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
