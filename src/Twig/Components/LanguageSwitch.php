<?php

namespace App\Twig\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class LanguageSwitch extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $currentLocal;

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->currentLocal = $requestStack->getCurrentRequest()->getLocale();
    }

    public function switch(string $locale): void
    {
        $this->requestStack->getCurrentRequest()->getSession()->set('_locale', $locale);
        $this->currentLocal = $locale;
        $this->requestStack->getCurrentRequest()->setLocale($locale);
    }


    public static function getComponentName(): string
    {
        return 'language_switch';
    }
}
