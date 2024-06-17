<?php

namespace App\Twig\Components;

use App\Entity\Company;
use App\Form\CompanyEditType;
use App\Service\CompanyFormHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class CompanyEditForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    private CompanyFormHelper $companyFormHelper;

    public function __construct(CompanyFormHelper $companyFormHelper)
    {
        $this->companyFormHelper = $companyFormHelper;
    }

    public function preFillForm(FormInterface $form, Company $company): void
    {
        $this->companyFormHelper->EditFormFields($form, $company);
    }

    /**
     * The initial data used to create the form.
     */
    #[LiveProp]
    public Company $initialFormData;

    protected function instantiateForm(): FormInterface
    {
        // we can extend AbstractController to get the normal shortcuts
        $form = $this->createForm(CompanyEditType::class, $this->initialFormData);

        $this->preFillForm($form, $this->initialFormData);

        return $form;
    }
}