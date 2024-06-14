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

        // Définition des champs du formulaire et de leur correspondance avec les propriétés de l'entité Company
        $fields = [
            'name' => 'companyField',
            'businessAddress' => 'businessAddressField',
            'postalCode' => 'postalCodeField',
            'city' => 'cityField',
            'owner' => [
                'firstname' => 'firstnameField',
                'lastname' => 'lastnameField'
            ]
        ];

        // Les champs du formulaire sont annotés avec les valeurs de l'entité Company
        foreach ($fields as $key => $value) {
            // Si tableau (champ 'owner' qui contient plusieurs sous-champs)
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $form->get($key)->get($subKey)->get($subValue)->setData($company->getOwner()->{"get" . ucfirst($subKey)}());
                }
            } else {
                $form->get($key)->get($value)->setData($company->{"get" . ucfirst($key)}());
            }
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($fields as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subKey => $subValue) {
                        // On récupère la valeur du sous-champ dans le formulaire et on la met à jour dans l'entité Company
                        $company->getOwner()->{"set" . ucfirst($subKey)}($form->get($key)->get($subKey)->get($subValue)->getData());
                    }
                } else {
                    $company->{"set" . ucfirst($key)}($form->get($key)->get($value)->getData());
                }
            }

            $entityManager->persist($company);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('edit.success'));

            return $this->redirectToRoute('app_company', ['id' => $company->getId()]);
        }

        return $this->render('company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }
}

