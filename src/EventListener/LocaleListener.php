<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LocaleListener
{
    private TokenStorageInterface $tokenStorage;
    private UserProviderInterface $userProvider;

    public function __construct(TokenStorageInterface $tokenStorage, UserProviderInterface $userProvider)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userProvider = $userProvider;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $token = $this->tokenStorage->getToken();
        if ($token) {
            $email = $token->getUser();
            $user = $this->userProvider->loadUserByUsername($email);
            if ($user) {
                $locale = $user->getLocale();
            } else {
                // Sinon, utilisez une valeur par défaut
                $locale = 'fr';
            }

            $request->setLocale($locale);
        }
    }
}