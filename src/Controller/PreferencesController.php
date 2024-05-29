<?php

namespace App\Controller;

use App\Form\DeleteAccountType;
use App\Form\PreferencesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class PreferencesController extends AbstractController
{
    #[Route('/preferences', name: 'app_preferences')]
    public function index(Request $request, TranslatorInterface $translator, ChartBuilderInterface $chartBuilder): Response
    {
        $preferencesForm = $this->createForm(PreferencesType::class);
        $deleteAccountForm = $this->createForm(DeleteAccountType::class);

        $chartViewProfil = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chartViewProfil->setData([
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'datasets' => [
                [
                    'label' => 'Nombre de vues de mon profil',
                    'backgroundColor' => 'blue',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 50, 100, 150, 200, 250, 300, 350, 400, 450, 500, 550],
                ],
            ],
        ]);

        $chartViewProfil->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 600,
                    'ticks' => [
                        'stepSize' => 50,
                    ],
                ],
            ],
            'responsive' => true,
        ]);

        $chartViewInvitations = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chartViewInvitations->setData([
            'labels' => ['Reçues'],
            'datasets' => [
                [
                    'label' => 'Nombre d\'évènements',
                    'backgroundColor' => 'black',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [50],
                ],
            ],
        ]);

        $chartViewInvitations->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 150,
                    'ticks' => [
                        'stepSize' => 50,
                    ],
                ],
                'x' => [
                    'barPercentage' => 0.5,
                    'categoryPercentage' => 0.5,
                ],
            ],
            'responsive' => true,
        ]);

        $chartViewEvents = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chartViewEvents->setData([
            'labels' => ['Suivis'],
            'datasets' => [
                [
                    'label' => 'Nombre d\'évènements',
                    'backgroundColor' => 'black',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [30],
                ],
            ],
        ]);

        $chartViewEvents->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 150,
                    'ticks' => [
                        'stepSize' => 50,
                    ],
                ],
                'x' => [
                    'barPercentage' => 0.5,
                    'categoryPercentage' => 0.5,
                ],
            ],
            'responsive' => true,
        ]);

        return $this->render('preferences/index.html.twig', [
            'controller_name' => 'PreferencesController',
            'preferences_form' => $preferencesForm->createView(),
            'delete_account_form' => $deleteAccountForm->createView(),
            'chart1' => $chartViewProfil,
            'chart2' => $chartViewInvitations,
            'chart3' => $chartViewEvents,
        ]);
    }
}
