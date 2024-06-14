<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        $token = $this->tokenStorage->getToken();
        if ($token) {
            $email = $token->getUser();
            $user = $this->userProvider->loadUserByIdentifier($email);
            if ($user) {
                $locale = $user->getLanguage();
            }

            if($locale !== $request->getLocale()) {

                $oldLocale = $request->getLocale();
                $request->setLocale($locale);
                $this->logger->info('Locale set to ' . $locale);

                $response = new RedirectResponse(str_replace($oldLocale, $locale, $path));
                $response->send();
            }
        }

    }
}