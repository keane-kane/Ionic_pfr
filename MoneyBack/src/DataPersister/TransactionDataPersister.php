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
use App\Services\SendSms;

class TransactionDataPersister implements  ContextAwareDataPersisterInterface
{
    use CalculFraitTrait;
    private $_entityManager;
    private $_security;
    private $moneyservice;
    private $repositoryTransaction;
    private $_sendSms;

    public function __construct(
        EntityManagerInterface $entityManager,
        MoneyServices $moneyservice,
        Security $security,
        TransactionRepository $transripo,
        SendSms $sendSms,
    ) {
        $this->_entityManager = $entityManager;
        $this->_security = $security;
        $this->moneyservice = $moneyservice;
        $this->transripo = $transripo;
        $this->_sendSms = $sendSms;

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
            // dd($curentuser);
            if ($data->getType() === "depot"  && !$data->getAnnulertransac()) 
            {
                $frais = $this->getFrait($data->getMontant());
                $data
                    ->setFrais($frais)
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

                // $this->_sendSms->send( $data->getMontant());
                $this->_entityManager->persist($data);
                $this->_entityManager->flush();
                return $data;
            }

            elseif($data->getType() === "retrait")
            {
                //code de retrait et phone client pour rechercher dans la base de donnees
                $codeRetrait = $data->getCode();
                $phoneClient = $data->getClientTrans()->getPhoneBeneficiaire();
                $clientRetrait = $this->transripo->findByCode($phoneClient, $codeRetrait);
                
                if(!empty($clientRetrait) && $data->getClientTrans()->getCniBeneficiaire() > 0)
                {
                    $clientRetrait = $clientRetrait[0];
                    if(!$clientRetrait->getCodeValide() && $curentuser->getMontant() == $data->getMontant())
                    
                    //$this->_entityManager->flush();
                    // $part = $clientRetrait->getPartRetrait();
                    //dd($clientRetrait);
                    $data
                         ->setFrais($clientRetrait->getFrais())
                         ->setUserAgenceTransaction($this->_security->getUser())
                         ->setDateDepot($clientRetrait->getDateDepot())
                         ->setDateRetrait(new \DateTime())
                         ->setPartEtat($clientRetrait->getPartEtat())
                         ->setPartTransfert($clientRetrait->getPartTransfert())
                         ->setPartRetrait($clientRetrait->getPartRetrait())
                         ->setPartDepot($clientRetrait->getPartDepot());

                    $curentuser->setMontant($curentuser->getMontant() + $clientRetrait->getMontant() + $clientRetrait->getPartRetrait() );
                   
                    $this->_entityManager->persist($data);
                    $clientRetrait->setCodeValide(true);
                    $this->_entityManager->flush();
                }else{
                  
                    dd("code invalide");
                }
    
              //  dd($clientRetrait);
              $this->_entityManager->flush();
              return $data;
            }
            else if($data->getType() == "depot" && $data->getAnnulertransac() )
            {
                    
                    $codeRetrait = $data->getCode();
                    $phoneClient = $data->getClientTrans()->getPhoneBeneficiaire();
                    $c = $this->transripo->findByCode($phoneClient, $codeRetrait);
                    if(!empty($c)) {

                        $clientRetrait = $c[0];
                        $montantDepot = $data->getMontant() + $data->getFrais();
                        $accountUserMadeDepot = $clientRetrait->getUserAgenceTransaction()->getAgencePartenaire()->getCompte();
                        $accountUserMadeDepot->setMontant($data->getMontant() + $accountUserMadeDepot->getMontant());
                        $clientRetrait->setCodeValide(true)
                        ->setAnnulertransac(true)
                        
                        ->setDateRetrait(new \DateTime());
                    //     dd($accountUserMadeDepot->getMontant());
        
                    //   dd($data);
        
                        $this->_entityManager->flush();
                        return $clientRetrait;
                    }else{
                        dd("code client non valide");
                    }
            }else{
                dd("Operations inconnue pour cet endpoint");
            }
            
            
            }
    
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
