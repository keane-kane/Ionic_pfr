<?php

namespace App\DataPersister;

use App\Entity\Profils;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

final class ProfilsDataPersister implements DataPersisterInterface
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function supports($data): bool
    {  
        return $data instanceof Profils;
    }

    public function persist($data)
    {   
        return $data;
    }

    public function remove($data)
    {
          $data->setArchive(true);
        $users = $data->getUsers();
        foreach($users as $u)
            $u->setArchive(true);

        $this->entityManager->flush();
        return $data;
    }
}