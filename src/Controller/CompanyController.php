<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyEditType;
use App\Repository\CompanyRepository;
use App\Service\CompanyFormHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CompanyController extends AbstractController
{

    private TranslatorInterface $translator;

    private CompanyFormHelper $companyFormHelper;

    public function __construct(TranslatorInterface $translator, CompanyFormHelper $companyFormHelper)
    {
        $this->translator = $translator;
        $this->companyFormHelper = $companyFormHelper;
    }

    #[Route('/company/{id}',
        name: 'app_company')]
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

    #[Route('/company/{id}/edit',
        name: 'app_company_edit',
        methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompanyEditType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->companyFormHelper->updateCompanyEntity($form, $company);

            $entityManager->persist($company);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('edit.success'));

            return $this->redirectToRoute('app_company', ['id' => $company->getId()]);
        }

        return $this->render('company/edit.html.twig', [
            'company' => $company,
            'editForm' => $form,
        ]);
    }
}

