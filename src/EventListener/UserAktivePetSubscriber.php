<?php
namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

/**
 * Stores the active pet of the user in the session after the login. 
 */
class UserAktivePetSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        /**
         * @var User
         */
        $user = $event->getUser();

        if (null !== $user->getActivePet()) {
            $this->requestStack->getSession()->set('_active_pet', $user->getActivePet());
        }

    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }
}