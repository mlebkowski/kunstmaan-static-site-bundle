<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Response\KernelResponse;

use Nassau\KunstmaanStaticSiteBundle\DependencyInjection\ValueObject\RouteSpecification;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

class KernelResponseFactory implements ResponseFactory
{
    /**
     * @var \Traversable|RouteSpecification[]
     */
    private $routes;

    /**
     * @var PathProvider
     */
    private $pathProvider;

    /**
     * @var KernelInterface
     */
    private $kernel;
    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @param \Traversable $routes
     * @param PathProvider $pathProvider
     * @param KernelInterface $kernel
     * @param RequestFactory $requestFactory
     */
    public function __construct(\Traversable $routes, PathProvider $pathProvider, KernelInterface $kernel, RequestFactory $requestFactory)
    {
        $this->routes = $routes;
        $this->pathProvider = $pathProvider;
        $this->kernel = $kernel;
        $this->requestFactory = $requestFactory;
    }


    /**
     * @return Response[]|\Generator
     */
    public function getResponses()
    {
        static $dispatcher;

        if (!$dispatcher) {
            $dispatcher = $this->kernel->getContainer()->get('event_dispatcher');
            $cache = $this->kernel->getContainer()->get('liip_imagine.cache.manager');

            $dispatcher->addListener(KernelEvents::RESPONSE, function (FilterResponseEvent $event) use ($cache) {
                $re = '#(?:(?:http[s]?:)?//[\w\d-]+(?:\.[\w\d-]+)*)?/media/cache/resolve/(?:[_\w\d]+/)+uploads/media/[\w\d]+/[\w\d_.-]+#i';

                $event->getResponse()->setContent(preg_replace_callback($re, function ($url) use ($cache) {

                    list ($url) = $url;

                    $subreq = $this->kernel->handle($this->requestFactory->createRequest($url));

                    return $subreq->headers->get('location');

                }, $event->getResponse()->getContent()));

            });
        }

        foreach ($this->routes as $route) {
            foreach ($this->pathProvider->getPaths($route->getGenerator(), $route->getRoute(), $route->getDefaults()) as $path) {
                yield $path => $this->kernel->handle($this->requestFactory->createRequest($path));
            }
        }
    }
}
