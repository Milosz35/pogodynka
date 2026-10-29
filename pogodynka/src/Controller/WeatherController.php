<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\ForecastRepository;
use App\Repository\PlaceRepository;
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
        ForecastRepository $forecastRepository
    ): Response
    {
        $place = $placeRepository->findByCityAndCountry($city, $country); // linijka przy użyciu copilot

        if (!$place) {
            throw $this->createNotFoundException('Nie znaleziono miasta');
        }

        $forecasts = $forecastRepository->findByLocation($place);

        return $this->render('weather/city.html.twig', [
            'place' => $place,
            'forecasts' => $forecasts,
        ]);
    }
}

