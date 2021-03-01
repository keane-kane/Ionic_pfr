<?php
// src/DataPersister/TransactionDataPersister.php

namespace App\DataPersister;

use App\Entity\Transaction;
use App\Services\MoneyServices;
use App\Services\CalculFraitTrait;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class TransactionDataPersister implements DataPersisterInterface
{
    use CalculFraitTrait;
    private $_entityManager;
    private $security;
    private $moneyservice;
    private $repositoryTransaction;

    public function __construct(
        EntityManagerInterface $entityManager,
        MoneyServices $moneyservice,
        Security $security,
        TransactionRepository $transripo
    ) {
        $this->_entityManager = $entityManager;
        $this->security = $security;
        $this->moneyservice = $moneyservice;
        $this->transripo = $transripo;

    }

    /**
     * {@inheritdoc}
     */
    public function supports($data): bool
    {
        return $data instanceof Transaction;
    }

    /**
     * @param Transaction $data
     */
    public function persist($data)
    {   
        if($data->getType() != "" && $data->getType() === "depot")
        {
            $oldesolde = $this->security->getUser();
            dd($oldesolde);
            if ($data->getFrais() == null)
            {
                $frais = $this->getFrait($data->getMontant());
                $data->setMontant($data->getMontant() - $frais);
                $com =  $this->getFrait($data->getMontant());
                if($frais > $com){ $data->setFrais($com); }
                else { $data->setFrais($com); }
                // dd($data);
            }
            if ($data->getMontant()) {
    
                $com = $this->getFrait($data->getMontant());
                $data
                    ->setPartEtat($com * 0.4)
                    ->setPartTransfert($com * 0.3)
                    ->setPartRetrait($com * 0.2)
                    ->setPartDepot($com * 0.1)
                    ->setCode($this->moneyservice->getCodetransaction($data->getClientdepot()->getCniClient()))
                    ->setUsertransaction($this->security->getUser())
                    ->setCreateAt(new \DateTime())
                    ;
                    $this->_entityManager->persist($data);
                    $this->_entityManager->flush();
                    dd($data);
            }
        }else if($data->getType() != "" && $data->getType() === "retrait")
        {
          
          $clientR = $this->transripo->findByCode($data->getClientretrait()->getPhoneBeneficiaire()
                 , $data->getCode()
         );
          dd($clientR);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data)
    {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}