<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    // /**
    //  * @return Transaction[] Returns an array of Transaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Transaction
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @return Transaction[] Returns an array of Transaction objects
     */
  
    public function findByCode($no, $code): ?Transaction
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->addSelect('c')
            ->Join('t.clientTrans', 'c')
            ->andWhere('t.code = :code')
            ->andWhere('t.codeValide = false')
            ->andWhere('t.annulertransac = false')
            ->orWhere('c.phoneBeneficiaire = :no',)
            ->setParameter('no', $no)
            ->setParameter('code', $code)
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findOneByIdOrCode($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.code = :val')
            ->orWhere('t.id=:val')
            ->andWhere('t.archive = :false')
            ->andWhere('t.annulertransac = :false')
            ->setParameter('val', $value)
            ->setParameter('false', false)
            ->setParameter('false', false)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
}
