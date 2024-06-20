<?php

namespace App\Controller;

use App\Form\ProductFormType;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{
    #[Route('/shop/{id}', name: 'app_shop')]
    public function index(CompanyRepository $companyRepository, int $id, Request $request): Response
    {
        $company = $companyRepository->find($id);

        $form = $this->createForm(ProductFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the form submission
        }

        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }
}
