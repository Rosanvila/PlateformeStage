<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompanyController extends AbstractController
{
    #[Route('/{_locale}/company/{id}', name: 'app_company',
        requirements: ['_locale' => '%app.supported_locales%'])]
    public function index(int $id, CompanyRepository $companyRepository): Response
    {
        $company = $companyRepository->find($id);

        if (!$company) {
            throw $this->createNotFoundException('The company does not exist');
        }

        return $this->render('company/index.html.twig', [
            'company' => $company,
        ]);
    }

    #[Route('/{_locale}/company/{id}/edit', name: 'app_company_edit',
        requirements: ['_locale' => '%app.supported_locales%'])]
    public function edit(int $id, CompanyRepository $companyRepository): Response
    {
        $company = $companyRepository->find($id);

        if (!$company) {
            throw $this->createNotFoundException('The company does not exist');
        }

        return $this->render('company/edit.html.twig', [
            'company' => $company,
        ]);
    }
}
