<?php

declare(strict_types=1);

namespace App\Helper;
use App\Entity\Character;

class ArrayToCharacterMapper implements MapperInterface
{
    public function map(array $data): Character
    {
        $character = new Character();
        if (isset($data['id'])) {
            $character->setId($data['id']);
        }
        if (isset($data['name'])) {
            $character->setName($data['name']);
        }
        if (isset($data['status'])) {
            $character->setStatus($data['status']);
        }
        if (isset($data['species'])) {
            $character->setSpecies($data['species']);
        }
        if (isset($data['type'])) {
            $character->setType($data['type']);
        }
        if (isset($data['gender'])) {
            $character->setGender($data['gender']);
        }
        if (isset($data['origin'])) {
            $character->setOrigin($data['origin']);
        }
        if (isset($data['location'])) {
            $character->setLocation($data['location']);
        }
        if (isset($data['image'])) {
            $character->setImage($data['image']);
        }
        if (isset($data['episode'])) {
            $character->setEpisode($data['episode']);
        }
        if (isset($data['url'])) {
            $character->setUrl($data['url']);
        }
        if (isset($data['created'])) {
            $character->setCreated(new \DateTime($data['created']));
        }

        return $character;
    }
}
