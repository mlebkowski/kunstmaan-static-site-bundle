<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Generator;

use Kunstmaan\AdminBundle\Helper\DomainConfigurationInterface;

class LocalesGenerator implements RouteParametersGenerator
{

    /**
     * @var DomainConfigurationInterface
     */
    private $domainConfiguration;

    /**
     * @param DomainConfigurationInterface $domainConfiguration
     */
    public function __construct(DomainConfigurationInterface $domainConfiguration)
    {
        $this->domainConfiguration = $domainConfiguration;
    }


    /**
     * @return array[]
     */
    public function getItems()
    {
        return array_map(function ($locale) {
            return [
                'locale' => $locale
            ];
        }, $this->domainConfiguration->getBackendLocales());
    }
}
