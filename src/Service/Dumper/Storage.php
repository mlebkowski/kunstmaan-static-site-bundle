<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Dumper;

interface Storage
{
    /**
     * @param string $location
     * @param StaticSiteDumper $dumper
     * @return \Generator|StaticItem[]
     */
    public function storeStaticSite($location, StaticSiteDumper $dumper);
}
