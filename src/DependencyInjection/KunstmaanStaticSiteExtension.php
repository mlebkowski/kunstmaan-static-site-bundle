<?php

namespace Nassau\KunstmaanStaticSiteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class KunstmaanStaticSiteExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        array_unshift($configs, [
            'files' => [
                'web' => [
                    'directory' => $container->getParameter('kernel.root_dir') . '/../web',
                    'exclude' => '*.php',
                ]
            ],
            'routes' => [
                'pages' => [
                    'route' => '_slug',
                    'generator' => 'nodes',
                ],
                'sitemap_index' => [
                    'route' => 'KunstmaanSitemapBundle_sitemapindex',
                    'defaults' => [
                        '_format' => 'xml',
                    ]
                ],
                'sitemaps' => [
                    'route' => 'KunstmaanSitemapBundle_sitemap',
                    'generator' => 'locales',
                    'defaults' => [
                        '_format' => 'xml',
                    ]
                ],
                'robots' => [
                    'route' => 'KunstmaanSeoBundle_robots',
                ]
            ]
        ]);

        $configs = (new Processor)->processConfiguration(new Configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('nassau.static_site.url_prefix', $configs['url_prefix']);
        $this->configureRoutes($container, $configs['routes']);
        $this->configureFiles($container, $configs['files']);
    }

    private function configureRoutes(ContainerBuilder $containerBuilder, array $routes)
    {
        $parent = $containerBuilder->getDefinition('nassau.static_site.dumper.routes');
        foreach ($routes as $key => $item) {
            $id = sprintf('nassau.static_site.dumper.routes.%s', $key);

            $containerBuilder->setDefinition($id, new Definition(ValueObject\RouteSpecification::class, [
                $item['route'], $item['generator'], $item['defaults']
            ]));

            $parent->addMethodCall('offsetSet', [$key, new Reference($id)]);
        }
    }

    private function configureFiles(ContainerBuilder $containerBuilder, array $files)
    {
        $parent = $containerBuilder->getDefinition('nassau.static_site.dumper.files');

        foreach ($files as $key => $item) {
            $id = sprintf('nassau.static_site.dumper.routes.%s', $key);

            $containerBuilder->setDefinition($id, new Definition(ValueObject\FilesSpecification::class, [
                $item['directory'], $item['target'], $item['include'], $item['exclude']
            ]));

            $parent->addMethodCall('offsetSet', [$key, new Reference($id)]);
        }

    }

}
