<?php

namespace App\ChartBuilders;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class PremiumChart
{
    public static function buildChart(ChartBuilderInterface $chartBuilder)
    {
        $charts = [];

        $chartViewLike = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chartViewLike->setData([
            'labels' => ['Posts likés', 'Commentaires likés'],
            'datasets' => [
                [
                    'label' => 'Nombre de likes',
                    'backgroundColor' => 'black',
                    'borderColor' => 'black',
                    'data' => [101, 75],
                ],
            ],
        ]);
        $chartViewLike->setOptions([
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
        $charts['like'] = $chartViewLike;

        $chartViewQuote = $chartBuilder->createChart(Chart::TYPE_PIE);
        $chartViewQuote->setData([
            'labels' => ["Devis 'Sécurité'", "Devis 'Cloud'", "Devis 'Storage'", "Devis 'Service'",
                ],
            'datasets' => [
                [
                    'label' => 'Nombre de devis',
                    'backgroundColor' => 'black',
                    'borderColor' => 'pink',
                    'data' => [40, 25, 15, 20],
                ],
            ],
        ]);

        $chartViewQuote->setOptions([
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
        $charts['quotation'] = $chartViewQuote;

        return $charts;
    }
}