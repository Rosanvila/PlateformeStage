<?php

namespace App\Controller;

use App\ChartBuilders\FreemiumChart;
use App\ChartBuilders\PremiumChart;
use App\Form\ChangePasswordFormType;
use App\Form\LanguagesFormType;
use App\Form\PreferencesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;

class PreferencesController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    #[Route(
        path: '/preferences',
        name: 'app_preferences',
    )]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, ChartBuilderInterface $chartBuilder): Response
    {

        $user = $this->getUser();
        $supportedLocales = $this->getParameter('app.supported_locales');

        $preferencesForm = $this->createForm(PreferencesType::class, $user);
        $changePasswordForm = $this->createForm(ChangePasswordFormType::class);
        $languagesForm = $this->createForm(LanguagesFormType::class, $user, [
            'supported_locales' => $supportedLocales
        ]);

        // Preferences & Username form
        $fields = ['firstname', 'lastname'];
        // Set the data of the form fields
        foreach ($fields as $field) {
            $preferencesForm->get($field)->get($field . 'Field')->setData($user->{"get" . ucfirst($field)}());
        }

        $preferencesForm->handleRequest($request);
        if ($preferencesForm->isSubmitted() && $preferencesForm->isValid()) {
            $firstname = $preferencesForm->get('firstname')->get('firstnameField')->getData();
            $lastname = $preferencesForm->get('lastname')->get('lastnameField')->getData();

            $user->setFirstname($firstname);
            $user->setLastname($lastname);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', $this->translator->trans('preferences.update_success'));
            return $this->redirectToRoute('app_preferences');
        }


        // Language switch form
        $languagesForm->handleRequest($request);
        if ($languagesForm->isSubmitted() && $languagesForm->isValid()) {
            $language = $languagesForm->getData()->getLanguage();
            $user->setLanguage($language);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $request->setLocale($language);

            $this->addFlash('success', $this->translator->trans('preferences.language_change_success'));
            return $this->redirectToRoute('app_preferences', ['_locale' => $language]);
        }

        // Password reset form
        $changePasswordForm->handleRequest($request);
        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {
            $email = $changePasswordForm->get('email')->getData();
            $currentPassword = $changePasswordForm->get('currentPassword')->getData();
            $plainPassword = $changePasswordForm->get('plainPassword')->get('password')->getData();

            // Vérifier si l'email correspond à celui de l'utilisateur en session
            if ($email !== $user->getEmail()) {
                $this->addFlash('error', $this->translator->trans('preferences.email_mismatch_error'));
                return $this->redirectToRoute('app_preferences');
            }

            // Vérifier si le mot de passe actuel est correct
            if (!$userPasswordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('error', $this->translator->trans('preferences.incorrect_password_error'));
                return $this->redirectToRoute('app_preferences');
            }

            // Si tout est bon, procédez au changement de mot de passe
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', $this->translator->trans('preferences.password_change_success'));
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
            'preferences_form' => $preferencesForm,
            'changePasswordForm' => $changePasswordForm,
            'languages_form' => $languagesForm,
            'chart1' => $chartViewProfil,
            'chart2' => $chartViewInvitations,
            'chart3' => $chartViewEvents,
            'chart4' => $chartViewLike,
            'chart5' => $chartViewQuote,
        ]);
    }
}
