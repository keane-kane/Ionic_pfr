<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Profile;
use App\Entity\Profils;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Transaction;

final class ArchiveDataPersister implements DataPersisterInterface
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function supports($data): bool
    {  
        switch($data){
            
            case $data: return $data instanceof Profils;
            break;
            case $data: return $data instanceof User;
            break;
            case $data: return $data instanceof Transaction;
            break;
        }
    }

    public function persist($data)
    {   
        return $data;
    }

    public function remove($data)
    {
        $data->setArchive(true);

        $this->entityManager->flush();
        return $data;
    }
}