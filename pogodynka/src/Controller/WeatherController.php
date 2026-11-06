<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\ForecastRepository;
use App\Repository\PlaceRepository;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{country}', name: 'app_weather')]
    public function city(
        string $city,
        string $country,
        PlaceRepository $placeRepository,
        WeatherUtil $util,
    ): Response
    {
        $place = $placeRepository->findByCityAndCountry($city, $country);

        if (!$place) {
            throw $this->createNotFoundException('Nie znaleziono miasta');
        }

        $forecasts = $util->getWeatherForPlace($place);

        return $this->render('weather/city.html.twig', [
            'place' => $place,
            'forecasts' => $forecasts,
        ]);
    }
}

