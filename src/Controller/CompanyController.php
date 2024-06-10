<?php

namespace App\Controller;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompanyController extends AbstractController
{
    #[Route('/company/{id}', name: 'app_company')]
    public function index(Company $company): Response
    {
        return $this->render('company/index.html.twig', [
            'company' => $company,
        ]);
    }
}
