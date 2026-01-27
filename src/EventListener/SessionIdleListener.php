<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security; 
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class SessionIdleListener
{
    private const MAX_IDLE_TIME = 900; // Time in seconds to logout after inactivity (15 minutes)

    public function __construct(
        private RequestStack $requestStack,
        private Security $security,
        private RouterInterface $router,
        private TokenStorageInterface $tokenStorage,
        private int $maxIdleTime = self::MAX_IDLE_TIME
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        
        if (!$event->isMainRequest()) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user) {
            return;
        }

        $session = $this->requestStack->getSession();
        $now = time();

        $lastActivity = $session->get('last_activity');
        if ($lastActivity !== null && ($now - $lastActivity) > self::MAX_IDLE_TIME) {
            $session->invalidate();
            $this->tokenStorage->setToken(null);

            $event->setResponse(
                new RedirectResponse($this->router->generate('app_login'))
            );
            return;
        }

        $session->set('last_activity', $now);
    }
}
