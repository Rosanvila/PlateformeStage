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

use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent(template: 'components/header/SearchInput.html.twig')]
class SearchInput
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?string $query = null;

    public function __construct(private readonly UserRepository $userRepository, private readonly CompanyRepository $companyRepository)
    {
    }

    #[ExposeInTemplate]
    public function getResults(): array
    {
        if(!is_null($this->query) && !empty($this->query)) {
            $resultsArray = [];
            $resultsArray['users'] = $this->userRepository->findSearchResults($this->query, 10);
            $resultsArray['companies'] = $this->companyRepository->findSearchResults($this->query, 10);
            return $resultsArray;
        }
        else {
            return [];
        }
    }
}
