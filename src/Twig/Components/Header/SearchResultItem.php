<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig\Components\Header;

use App\Service\Feed\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\User;
use App\Entity\Company;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[AsLiveComponent(template: 'components/header/SearchResultItem.html.twig')]
class SearchResultItem extends AbstractController
{
    use ComponentToolsTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public ?User $user = null;

    #[LiveProp]
    public ?Company $company = null;

    public function __construct(private readonly Security $security, private readonly EntityManagerInterface $entityManager)
    {
    }

    #[ExposeInTemplate]
    public function isFollowedByUser(): bool
    {
        if(!is_null($this->user)) {
            return $this->user->getFollowers()->contains($this->security->getUser());
        }

        return false;
    }

    #[LiveAction]
    public function follow(): void
    {
        if(!is_null($this->user)) {
            if($this->user->getFollowers()->contains($this->security->getUser())) {
                $this->user->getFollowers()->removeElement($this->security->getUser());
                $this->security->getUser()->getFollows()->removeElement($this->user);
            } else {
                $this->user->getFollowers()->add($this->security->getUser());
                $this->security->getUser()->getFollows()->add($this->user);
            }

            $this->entityManager->flush();
        }
    }

}
