<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Response\KernelResponse;

use Symfony\Component\HttpFoundation\Request;

class RequestFactory
{
    private $prefix;

    public function __construct($prefix)
    {
        $this->prefix = rtrim($prefix, '/');
    }

    /**
     * @param string $url
     * @return Request
     */
    public function createRequest($url)
    {
        if ($this->needsPrefix($url)) {
            $url = $this->prefix . '/' . ltrim($url, '/');
        }

        return Request::create($url);
    }

    /**
     * @param string $url
     * @return bool
     */
    protected function needsPrefix($url)
    {
        return "" === (string)parse_url($url, PHP_URL_HOST);
    }
}
