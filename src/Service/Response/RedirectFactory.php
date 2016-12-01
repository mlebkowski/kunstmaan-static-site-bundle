<?php

namespace Nassau\KunstmaanStaticSiteBundle\Service\Response;

use Kunstmaan\RedirectBundle\Entity\Redirect;
use Kunstmaan\RedirectBundle\Repository\RedirectRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RedirectFactory implements ResponseFactory
{
    /**
     * @var RedirectRepository
     */
    private $redirectRepository;

    /**
     * @param RedirectRepository $redirectRepository
     */
    public function __construct(RedirectRepository $redirectRepository)
    {
        $this->redirectRepository = $redirectRepository;
    }


    /**
     * @return Response[]|\Generator
     */
    public function getResponses()
    {
        /** @var Redirect $redirect */
        foreach ($this->redirectRepository->findAll() as $redirect) {
            $code = $redirect->isPermanent() ? RedirectResponse::HTTP_MOVED_PERMANENTLY : RedirectResponse::HTTP_FOUND;

            yield $redirect->getOrigin() => new RedirectResponse($redirect->getTarget(), $code);
        }
    }
}
