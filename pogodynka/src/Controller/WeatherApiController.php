<?php

namespace App\Controller;

use App\Entity\Forecast;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class WeatherApiController extends AbstractController
{
    #[Route(
        '/api/v1/weather',
        name: 'app_weather_api',
        methods: ['GET']
    )]
    public function index(
        WeatherUtil $weatherUtil,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter] bool $twig = false,
    ): Response {

        $forecasts = $weatherUtil->getWeatherForCountryAndCity(
            $country,
            $city
        );

        // fragment napisany z copilot
        if (!in_array($format, ['json', 'csv'], true)) {
            return $this->json([
                'error' => 'Invalid format. Use json or csv.',
            ], 400);
        }

        if ($twig) {
            if ($format === 'csv') {
                $content = $this->renderView(
                    'weather_api/index.csv.twig',
                    [
                        'city' => $city,
                        'country' => $country,
                        'forecasts' => $forecasts,
                    ]
                );

                return new Response($content, 200, [
                    'Content-Type' => 'text/csv; charset=UTF-8',
                ]);
            }

            $content = $this->renderView(
                'weather_api/index.json.twig',
                [
                    'city' => $city,
                    'country' => $country,
                    'forecasts' => $forecasts,
                ]
            );

            return new Response($content, 200, [
                'Content-Type' => 'application/json; charset=UTF-8',
            ]);
        }
        if ($format === 'csv') {

            $csv = "city,country,date,celsius,fahrenheit\n";

            foreach ($forecasts as $forecast) {
                $csv .= sprintf(
                    "%s,%s,%s,%s\n",
                    $city,
                    $country,
                    $forecast->getDate()->format('Y-m-d'),
                    $forecast->getTemperature(),
                    $forecast->getFahrenheit()
                );
            }

            return new Response($csv, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }
        return $this->json([
            'city' => $city,
            'country' => $country,
            'forecasts' => array_map(
                fn(Forecast $forecast) => [
                    'date' => $forecast->getDate()->format('Y-m-d'),
                    'celsius' => $forecast->getTemperature(),
                    'fahrenheit' => $forecast->getFahrenheit(),
                ],
                $forecasts
            ),
        ]);
    }
}