<?php
// src/DataPersister/TransactionDataPersister.php

namespace App\DataPersister;

use App\Entity\Transaction;
use App\Services\MoneyServices;
use App\Services\CalculFraitTrait;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class TransactionDataPersister implements  ContextAwareDataPersisterInterface
{
    use CalculFraitTrait;
    private $_entityManager;
    private $_security;
    private $moneyservice;
    private $repositoryTransaction;

    public function __construct(
        EntityManagerInterface $entityManager,
        MoneyServices $moneyservice,
        Security $security,
        TransactionRepository $transripo
    ) {
        $this->_entityManager = $entityManager;
        $this->_security = $security;
        $this->moneyservice = $moneyservice;
        $this->transripo = $transripo;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, $context = []): bool
    {
        return $data instanceof Transaction;
    }

    /**
     * @param Transaction $data
     */
    public function persist($data, $context = [])
    {
        if ((($context['collection_operation_name']) ?? null) === 'post') {
            //current user of account
            $curentuser = $this->_security->getUser()->getAgencePartenaire()->getCompte();
          
            if ($data->getType() === "depot") 
            {
                $frais = $this->getFrait($data->getMontant());
                $data
                    ->setPartEtat($frais * 0.4)
                    ->setPartTransfert($frais * 0.3)
                    ->setPartRetrait($frais * 0.2)
                    ->setPartDepot($frais * 0.1)
                    ->setCode($this->moneyservice->getCodetransaction($data->getClientTrans()->getCniClient()))
                    ->setUserAgenceTransaction($this->_security->getUser())
                    ->setDateDepot(new \DateTime())
                    ;
                //update compte usercurrent et verification du montant
                
                if($curentuser->getMontant() < 5000 )
                    dd("le solde de votre compte ne vous permet pas de faire un depôt");
                
                $montantDepot = $data->getMontant() - $frais;
                $curentuser->setMontant($curentuser->getMontant() - $montantDepot + $data->getPartDepot() );
            
            }

            elseif($data->getType() === "retrait")
            {
                //code de retrait et phone client pour rechercher dans la base de donnees
                $codeRetrait = $data->getCode();
                $phoneClient = $data->getClientTrans()->getPhoneBeneficiaire();
                $clientRetrait = $this->transripo->findByCode($phoneClient, $codeRetrait);
                
              // dd($clientRetrait);
                if(!empty($clientRetrait))
                {
                    $clientRetrait = $clientRetrait[0];
                    if(!$clientRetrait->getCodeValide() && $curentuser->getMontant() == $data->getMontant())
                    
                    $this->_entityManager->flush();
                    $partRetrait = $clientRetrait->getPartRetrait();
                    $data
                         ->setPartRetrait($partRetrait)
                         ->setUserAgenceTransaction($this->_security->getUser())
                         ->setDateDepot($clientRetrait->getDateDepot())
                         ->setDateRetrait(new \DateTime());

                    $curentuser->setMontant($curentuser->getMontant() + $clientRetrait->getMontant() + $partRetrait );
                    $this->_entityManager->persist($data);
                    $this->_entityManager->flush();
                }else{
                  
                    dd("code invalide");
                }
    
            }
            
            $clientRetrait->setCodeValide(true);
            $this->_entityManager->flush();
           // dd($clientRetrait);
            return $data;
            
        }
        else if ((($context['item_operation_name']) ?? null) === 'PUT')
            {
                if($data->getType() == "depot" && $data->getAnnulertransac() )
                {
                    //dd($data);
                    $montantDepot = $data->getMontant() + $data->getFrais();
                    $accountUserMadeDepot = $data->getUserAgenceTransaction()->getAgencePartenaire()->getCompte();
                    $accountUserMadeDepot->setMontant($data->getMontant() + $accountUserMadeDepot->getMontant());
                    $data->setAnnulertransac(true);
                    $data->setCodeValide(true)
                    
                    ->setDateRetrait(new \DateTime());

                   dd($data);
    
                    $this->_entityManager->flush();
                    return $data;
                }else{
                    dd("Operations inconnue pour cet endpoint");
                }
            }
    //    // dd($this->security->getUser()->getAgencePartenaire()->getCompte()->getMontant());
    //     $soldeCompte = $this->security->getUser()->getAgencePartenaire()->getCompte()->getMontant();
    //     $compte = $this->security->getUser()->getAgencePartenaire()->getCompte();
    //     if ($data->getType() != "" && $data->getType() === "depot") {

    //         $frais = 0;
    //         $soldeCompte = 0;
    //         $soldeTransmis = 0; 
    //         if ($data->getFrais() == null) {
    //             $frais = $this->getFrait($data->getMontant());
    //             $data->setMontant($data->getMontant() - $frais);
    //             $com =  $this->getFrait($data->getMontant());
    //             if ($frais > $com) {
    //                 $data->setFrais($com);
    //             } else {
    //                 $data->setFrais($com);
    //             }
    //             // dd($data);
    //         }
    //         if ($data->getMontant() > 0) {

    //             // $soldeCompte = $this->security->getUser()->getAgencePartenaire()->getCompte()->getMontant();
    //             // $compte = $this->security->getUser()->getAgencePartenaire()->getCompte();
    //            // dd($this->security->getUser()->getAgencePartenaire()->getCompte());
    //             $com = $this->getFrait($data->getMontant());
    //             $data
    //                 ->setPartEtat($com * 0.4)
    //                 ->setPartTransfert($com * 0.3)
    //                 ->setPartRetrait($com * 0.2)
    //                 ->setPartDepot($com * 0.1)
    //                 ->setCode($this->moneyservice->getCodetransaction($data->getClientTrans()->getCniClient()))
    //                 ->setUserAgenceTransaction($this->security->getUser())
    //                 ->setDateDepot(new \DateTime())
    //             ;
    //             //update compte usercurrent et verification du montant

    //             $compte->setMontant(($soldeCompte - $data->getMontant()) + $com * 0.1);
            
    //             $this->_entityManager->persist($data);
    //             $this->_entityManager->flush();
    //             return $data;
    //             //dd($data);
    //         }
    //     } else if ($data->getType() != "" && $data->getType() === "retrait") {

    //         $clientR = $this->transripo->findByCode(
    //             $data->getClientTrans()->getPhoneBeneficiaire(),
    //             $data->getCode()
    //         );
    //         if($clientR != null)
    //             dd("cet code n'existe pas");
            
    //         dd($clientR);
    //         $clientR[0]
    //                 ->setUserAgenceTransaction($this->security->getUser())
    //                 ->setDateRetrait(new \DateTime())
    //                 ->setType($data->getType())
    //                 ;
    //                 $compte->setMontant(($soldeCompte + $data->getMontant()) + $clientR->getPartRetrait());
    //         $this->_entityManager->persist($clientR);
    //         $this->_entityManager->flush();
    //         return $clientR;
    //     }
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, $context = [])
    {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}
