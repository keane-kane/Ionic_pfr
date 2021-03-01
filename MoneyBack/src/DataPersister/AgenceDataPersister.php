<?php

namespace App\DataPersister;

use App\Entity\Agence;
use App\Services\MoneyServices;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class AgenceDataPersister implements ContextAwareDataPersisterInterface
{

    private $entityManager;
    private $security;
    private $moneyservce;
    private $_passwordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        MoneyServices $moneyservce,
        AgenceRepository $agenceRepository,
        UserPasswordEncoderInterface $passwordEncoder
        )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->moneyservce = $moneyservce;
        $this->agenceRepository = $agenceRepository;
        $this->_passwordEncoder = $passwordEncoder;
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
        if ($data->getAdminagence()->getPassword()) {
            $data->getAdminagence()->setPassword(
                $this->_passwordEncoder->encodePassword(
                    $data->getAdminagence(),
                    $data->getAdminagence()->getPassword()
                )
            );

            $data->getAdminagence()->eraseCredentials();
        }

     
        if(isset($contexts["collection_operation_name"])){

            if(!empty($data->getAppartient())){
                $montant = $data->getAppartient()->getMontant();
    
                if($montant == null || $montant < 700000 )
                dd( "le montant ne doit pas être null ou < 700M");
                
                $data->getAppartient()->setCode($this->moneyservce->getCodeAgence($lastId+1));
                $data->getAppartient()->setCreateAt(new \DateTime());
                $data->setAdminsystem($this->security->getUser());
                
                 dd($data);
                $this->entityManager->persist($data);
                $this->entityManager->flush();
                return $data;
            }

        }else if(isset($contexts["item_operation_name"])){
           $agence = $this->agenceRepository->findOneBy(["id" => $data->getId()]);
            $montant = $data->getAppartient()->getMontant();
            
            if($montant == null || $montant < 700000 )
            dd( "le montant ne doit pas être null ou < 700M");
            
            $data->getAppartient()->setCode($data->getAppartient()->getCode());
            $data->getAppartient()->setCreateAt(new \DateTime());
            //$data->getAppartient()->setId($data->getAppartient()->getId());
            $data->setAdminsystem($this->security->getUser());
            dd($agence);
              $this->entityManager->persist($data);
              $this->entityManager->flush();
              return $data;
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