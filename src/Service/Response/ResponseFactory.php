<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Response;

use Symfony\Component\HttpFoundation\Response;

interface ResponseFactory
{
    /**
     * @return Response[]|\Generator
     */
    public function getResponses();
}
