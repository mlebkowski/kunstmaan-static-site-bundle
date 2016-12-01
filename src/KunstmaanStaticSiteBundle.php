<?php

namespace Nassau\KunstmaanStaticSiteBundle;

use Nassau\RegistryCompiler\RegistryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KunstmaanStaticSiteBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        (new RegistryCompilerPass)->register($container);
    }

}
