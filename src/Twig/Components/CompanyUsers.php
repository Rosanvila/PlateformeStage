<?php
namespace App\Twig\Components;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('company_users')]
class CompanyUsers extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?Company $company = null;

    #[LiveProp(writable: true)]
    public int $visibleUsersCount = 3;

    public function getVisibleUsers(): array
    {
        //return array_slice($this->company->getUsers()->toArray(), 0, $this->visibleUsersCount);
        $array = array(1,1,1,1,1,1,1,1,1,1);
        return array_slice($array, 0, $this->visibleUsersCount);
    }

    public function incrementVisibleUsersCount(): void
    {
        $this->visibleUsersCount += 3;
    }
}

