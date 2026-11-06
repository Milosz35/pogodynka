<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\PlaceRepository;
use App\Service\WeatherUtil;

#[AsCommand(
    name: 'weather:place',
    description: 'Add a short description for your command',
)]
class WeatherPlaceCommand{

    public function __construct(
        private readonly PlaceRepository $placeRepository,
        private readonly WeatherUtil $weatherUtil,
    ) {
    }
    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Place ID')] int $id,
    ): int {
        $place = $this->placeRepository->find($id);

        $forecasts = $this->weatherUtil->getWeatherForPlace($place);
        $io->writeln(sprintf('Place: %s', $place->getName()));
        $io->table(['Date', 'Temperature'], array_map(fn($m) => [
            $m->getDate()->format('Y-m-d'),
            $m->getTemperature(),
        ], $forecasts));

        return Command::SUCCESS;
    }
}
