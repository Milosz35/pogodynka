<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Display weather forecast for a city and country',
)]
class WeatherCityCommand
{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code')] string $countryCode,
        #[Argument('City name')] string $city,
    ): int {
        $forecasts = $this->weatherUtil
            ->getWeatherForCountryAndCity($countryCode, $city);

        $io->writeln(sprintf(
            'Location: %s (%s)',
            $city,
            $countryCode
        ));

        if (empty($forecasts)) {
            $io->warning('No weather forecasts found.');
            return Command::SUCCESS;
        }

        $io->table(
            ['Date', 'Temperature'],
            array_map(fn($forecast) => [
                $forecast->getDate()->format('Y-m-d'),
                $forecast->getTemperature(),
            ], $forecasts)
        );

        return Command::SUCCESS;
    }
}
