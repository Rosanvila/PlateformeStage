<?php

namespace App\ChartBuilders;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class FreemiumChart
{
    public static function buildChart(ChartBuilderInterface $chartBuilder)
    {
        $charts = [];

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

        $charts['profile'] = $chartViewProfil;

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

        $charts['invitations'] = $chartViewInvitations;

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

        $charts['events'] = $chartViewEvents;

        return $charts;
    }
}
