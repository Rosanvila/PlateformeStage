<?php

namespace App\EventListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LocaleListener
{
    private TokenStorageInterface $tokenStorage;
    private UserProviderInterface $userProvider;

    public function __construct(TokenStorageInterface $tokenStorage, UserProviderInterface $userProvider, ParameterBagInterface $params)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userProvider = $userProvider;
        $this->supportedLocales = $params->get('app.supported_locales');
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $token = $this->tokenStorage->getToken();
        if ($token) {
            $email = $token->getUser();
            $user = $this->userProvider->loadUserByIdentifier($email);
            if ($user) {
                $locale = $user->getLanguage();
            } else {
                // Default language
                $locale = 'fr';
            }

            $request->setLocale($locale);
        }
    }
}