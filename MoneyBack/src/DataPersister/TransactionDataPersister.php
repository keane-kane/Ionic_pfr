<?php
// src/DataPersister/TransactionDataPersister.php

namespace App\DataPersister;

use App\Entity\Transaction;
use App\Services\CalculFraitTrait;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;


class TransactionDataPersister implements DataPersisterInterface
{
    use CalculFraitTrait;
    private $_entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->_entityManager = $entityManager;
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
        if ($data->getMontant()) {

            $com = $this->getFrait($data->getMontant());
            $data
                ->setPartEtat($com * 0.4)
                ->setPartTransfert($com * 0.3)
                ->setPartRetrait($com * 0.2)
                ->setPartDepot($com * 0.1)
                ;
            dd($data);
            $this->_entityManager->persist($data);
            $this->_entityManager->flush();
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