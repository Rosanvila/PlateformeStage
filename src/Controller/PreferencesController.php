<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\ChartBuilders\FreemiumChart;
use App\ChartBuilders\PremiumChart;
use App\Form\ChangePasswordFormType;
use App\Form\PreferencesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;

class PreferencesController extends AbstractController
{

    private $entityManager; // Déclaration de la variable

    // Injection de EntityManagerInterface dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/preferences', name: 'app_preferences')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, ChartBuilderInterface $chartBuilder): Response
    {
        $preferencesForm = $this->createForm(PreferencesType::class);
        $changePasswordForm = $this->createForm(ChangePasswordFormType::class);

        $user = $this->getUser();
        $changePasswordForm->handleRequest($request);

        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {

            $plainPassword = $changePasswordForm->get('plainPassword')->get('password')->getData();

            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_preferences');
        }


        $chartsFreemium = FreemiumChart::buildChart($chartBuilder);
        $chartsPremium = PremiumChart::buildChart($chartBuilder);
        $chartViewProfil = $chartsFreemium['profile'];
        $chartViewInvitations = $chartsFreemium['invitations'];
        $chartViewEvents = $chartsFreemium['events'];
        $chartViewLike = $chartsPremium['like'];
        $chartViewQuote = $chartsPremium['quotation'];

        return $this->render('preferences/index.html.twig', [
            'controller_name' => 'PreferencesController',
            'preferences_form' => $preferencesForm->createView(),
            'changePasswordForm' => $changePasswordForm->createView(),
            'chart1' => $chartViewProfil,
            'chart2' => $chartViewInvitations,
            'chart3' => $chartViewEvents,
            'chart4' => $chartViewLike,
            'chart5' => $chartViewQuote,
        ]);
    }
}
