<?php

namespace App\DataPersister;

use App\Entity\Agence;
use Faker\UniqueGenerator;
use App\Services\MoneyServices;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use phpDocumentor\Reflection\Types\Context;
use PhpParser\Node\Stmt\Foreach_;

final class AgenceDataPersister implements ContextAwareDataPersisterInterface
{

    private $entityManager;
    private $security;
    private $moneyservce;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        MoneyServices $moneyservce,
        AgenceRepository $agenceRepository
        )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->moneyservce = $moneyservce;
        $this->agenceRepository = $agenceRepository;
    }

    public function supports($data , $contexts = []): bool
    {  
        return $data instanceof Agence;
    
    }

    public function persist($data, $contexts = [])
    {   
        $lastId = 0;
        if($this->agenceRepository->getLastId()){

            $lastId = $this->agenceRepository->getLastId()[0]->getId();
        }
        
        if($contexts["collection_operation_name"] === "post"){

            if(!empty($data->getAppartient())){
                $montant = $data->getAppartient()->getMontant();
    
                if($montant == null || $montant < 700000 )
                dd( "le montant ne doit pas être null ou < 700M");
                
                $data->getAppartient()->setCode($this->moneyservce->getCodeAgence($lastId+1));
                $data->getAppartient()->setCreateAt(new \DateTime());
                
                $this->entityManager->persist($data);
                $this->entityManager->flush();
                dd($montant);
                return $data;
            }

        }else{
            dd($contexts['item_operation_name']);
        }
        
    }

    public function remove($data, $contexts = [])
    {
        if($contexts["item_operation_name"] === "DELETE"){

             $data->setArchive(true);
             $data->getAdminagence()->setArchive(true);
             $users = $data->getAdminagence()->getUseragence();
             foreach ($users as $key => $user) {
                $user->setArchive(true);
             }
             $this->entityManager->flush();
        }
            
        return $data;
    }
}