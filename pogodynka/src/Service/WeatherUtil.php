<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Place;
use App\Entity\Forecast;
use App\Repository\PlaceRepository;
use App\Repository\ForecastRepository;

class WeatherUtil
{
    public function __construct(
        private readonly PlaceRepository $placeRepository,
        private readonly ForecastRepository $forecastRepository,
    ) {
    }
    /**
     * @return Forecast[]
     */
    public function getWeatherForPlace(Place $place): array
    {
        return $this->forecastRepository->findBy(
            ['Place' => $place],
            ['date' => 'ASC']
        );
    }

    /**
     * @return Place[]
     */
    public function getWeatherForCountryAndCity(
        string $countryCode,
        string $city
    ): array {
        $place = $this->placeRepository->findOneBy([
            'country' => $countryCode,
            'name' => $city,
        ]);

        if ($place === null) {
            return [];
        }

        return $this->getWeatherForPlace($place);
    }
}
