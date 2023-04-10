<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetCharacterByNameController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(string $name)
    {
        $characterRepository = $this->entityManager->getRepository(Character::class);

        $character = $characterRepository->findOneBy(['name' => $name]);

        if (!$character) {
            $response = new JsonResponse(
                new \ArrayObject(["error" => sprintf('Character of name %s not found', $name)]),
                Response::HTTP_NOT_FOUND
            );
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        return $character;
    }
}