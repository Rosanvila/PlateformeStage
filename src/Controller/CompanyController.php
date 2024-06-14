<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyEditType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CompanyController extends AbstractController
{

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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
        requirements: ['_locale' => '%app.supported_locales%'],
        methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompanyEditType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nameField = $form->get('name')->get('companyField')->getData();
            $businessAddressField = $form->get('businessAddress')->get('businessAddressField')->getData();
            $postalCodeField = $form->get('postalCode')->get('postalCodeField')->getData();
            $cityField = $form->get('city')->get('cityField')->getData();
            $firstnameField = $form->get('owner')->get('firstname')->get('firstnameField')->getData();
            $lastnameField = $form->get('owner')->get('lastname')->get('lastnameField')->getData();

            $company->setName($nameField);
            $company->setBusinessAddress($businessAddressField);
            $company->setPostalCode($postalCodeField);
            $company->setCity($cityField);
            $company->getOwner()->setFirstname($firstnameField);
            $company->getOwner()->setLastname($lastnameField);

            $entityManager->persist($company);
            $entityManager->flush();

            // Si formulaire valide, on redirige vers la page de l'entreprise
            // avec un message de succès
            $this->addFlash('success', $this->translator->trans('edit.success'));

            return $this->redirectToRoute('app_company', ['id' => $company->getId()]);
        }

        return $this->render('company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }
}
