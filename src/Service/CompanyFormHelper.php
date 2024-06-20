<?php
namespace App\Service;

use App\Entity\Company;
use Symfony\Component\Form\FormInterface;

class CompanyFormHelper
{
    private array $fields = [
        'name' => 'companyField',
        'businessAddress' => 'businessAddressField',
        'postalCode' => 'postalCodeField',
        'city' => 'cityField',
        /*'owner' => [
            'firstname' => 'firstnameField',
            'lastname' => 'lastnameField'
        ]*/
    ];

    public function EditFormFields(FormInterface $form, Company $company): void
    {
        foreach ($this->fields as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $form->get($key)->get($subKey)->get($subValue)->setData($company->getOwner()->{"get" . ucfirst($subKey)}());
                }
            } else {
                $form->get($key)->get($value)->setData($company->{"get" . ucfirst($key)}());
            }
        }
    }

    public function updateCompanyEntity(FormInterface $form, Company $company): void
{
    foreach ($this->fields as $key => $value) {
        if (is_array($value)) {
            foreach ($value as $subKey => $subValue) {
                $company->getOwner()->{"set" . ucfirst($subKey)}($form->get($key)->get($subKey)->get($subValue)->getData());
            }
        } else {
            $company->{"set" . ucfirst($key)}($form->get($key)->get($value)->getData());
        }
    }
}
}