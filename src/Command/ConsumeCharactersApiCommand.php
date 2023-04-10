<?php

declare(strict_types=1);

namespace App\Command;

use App\Helper\ApiConsumerHelper;
use App\Helper\MapperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ConsumeCharactersApiCommand extends Command
{
    private const RICK_AND_MORTY_API_ENDPOINT = "https://rickandmortyapi.com/api/character";
    protected static $defaultName = 'app:consume-characters-api';
    protected static $defaultDescription = 'Read characters from https://rickandmortyapi.com/ and populate database';

    private ApiConsumerHelper $consumerHelper;
    private MapperInterface $mapper;
    private EntityManagerInterface $em;

    public function __construct(
        ApiConsumerHelper $consumerHelper,
        MapperInterface $mapper,
        EntityManagerInterface $em
    ) {
        $this->consumerHelper = $consumerHelper;
        $this->mapper = $mapper;
        $this->em = $em;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Consuming Mortys',
            '============',
        ]);
        try {
            $this->clearTable();
            $characters = $this->fetchCharacters();
            $this->saveCharacters($characters);
        } catch (Exception $e) {
            $output->writeln(sprintf(
                'An error occured while importing Characters. Msg: %s',
                $e->getMessage())
            );

            return Command::FAILURE;
        }
        $output->writeln(sprintf('Imported %d characters', count($characters)));

        return Command::SUCCESS;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function fetchCharacters(): array
    {
        $characters = [];
        $page = 1;
        do {
            $result = $this->consumerHelper->fetchData(self::RICK_AND_MORTY_API_ENDPOINT, $page);
            if (isset($result['results'])) {
                foreach ($result['results'] as $value) {
                    if (isset($value['name']) && array_key_exists($value['name'], $characters)) {
                        continue;
                    }
                    $mappedEntity = $this->mapper->map($value);
                    $characters[$value['name']] = $mappedEntity;
                }
            }
            $page++;
        } while (!empty($result));

        return $characters;
    }

    private function saveCharacters(array $data): void
    {
        foreach ($data as $character) {
            $this->em->persist($character);
        }
        $this->em->flush();
    }

    private function clearTable(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\Character c')->execute();
    }
}
