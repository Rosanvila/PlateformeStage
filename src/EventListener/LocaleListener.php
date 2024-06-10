<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LocaleListener
{
    private LoggerInterface $logger;

    private TokenStorageInterface $tokenStorage;
    private UserProviderInterface $userProvider;
    private string $supportedLocales;

    public function __construct(LoggerInterface $logger, TokenStorageInterface $tokenStorage, UserProviderInterface $userProvider, ParameterBagInterface $params)
    {
        $this->logger = $logger;
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
                $locale = $request->getPreferredLanguage(explode('|', $this->supportedLocales));
            }

            $request->setLocale($locale);
            $this->logger->info('Locale set to ' . $locale);
        } else {
            // If there is no token, set the locale to the default language
            $request->setLocale($request->getPreferredLanguage(explode('|', $this->supportedLocales)));
        }
    }
}