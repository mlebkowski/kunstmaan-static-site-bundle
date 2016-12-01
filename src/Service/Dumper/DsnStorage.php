<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Dumper;

class DsnStorage implements Storage
{
    /**
     * @var Storage[]
     */
    private $storage = [];

    public function addStorage($scheme, Storage $storage)
    {
        $this->storage[$scheme] = $storage;
    }

    /**
     * @param string $location
     * @param StaticSiteDumper $dumper
     * @return \Generator|StaticItem[]
     */
    public function storeStaticSite($location, StaticSiteDumper $dumper)
    {
        $scheme = strtolower(parse_url($location, PHP_URL_SCHEME));

        $storage = isset($this->storage[$scheme]) ? $this->storage[$scheme] : null;

        if (null === $storage) {
            throw new \InvalidArgumentException(sprintf('There is no backend handling %s scheme', $scheme));
        }

        foreach ($storage->storeStaticSite($location, $dumper) as $key => $value) {
            yield $key => $value;
        }
    }
}
