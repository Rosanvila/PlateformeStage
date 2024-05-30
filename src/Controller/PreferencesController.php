<?php

namespace App\Controller;

use App\ChartBuilders\FreemiumChart;
use App\ChartBuilders\PremiumChart;
use App\Form\ChangeAccountPasswordType;
use App\Form\PreferencesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;

class PreferencesController extends AbstractController
{
    #[Route('/preferences', name: 'app_preferences')]
    public function index(Request $request, TranslatorInterface $translator, ChartBuilderInterface $chartBuilder): Response
    {
        $preferencesForm = $this->createForm(PreferencesType::class);
        $ChangeAccountPasswordForm = $this->createForm(ChangeAccountPasswordType::class);

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
            'changeAccountPassword_form' => $ChangeAccountPasswordForm->createView(),
            'chart1' => $chartViewProfil,
            'chart2' => $chartViewInvitations,
            'chart3' => $chartViewEvents,
            'chart4' => $chartViewLike,
            'chart5' => $chartViewQuote,
        ]);
    }
}
