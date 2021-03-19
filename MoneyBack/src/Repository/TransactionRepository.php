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
  
    public function findByCode($no, $code)
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->addSelect('c')
            ->Join('t.clientTrans', 'c')
            ->andWhere('t.code = :code')
            ->andWhere('c.phoneBeneficiaire = :no',)
            ->andWhere('t.codeValide = :codev')
            ->andWhere('t.type = :type')
            ->andWhere('t.annulertransac = :trans')
            ->andWhere('t.archive = :avchi')
            ->setParameter('no', $no)
            ->setParameter('code', $code)
            ->setParameter('type', 'depot')
            ->setParameter('codev', 0)
            ->setParameter('trans', 0)
            ->setParameter('avchi', 0)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findOneByIdOrCode($value): ?Transaction
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.code = :val')
            ->orWhere('t.id=:val')
            ->andWhere('t.archive = :archive')
            ->andWhere('t.codeValide = :codev')
            ->andWhere('t.annulertransac = :trans')
            ->setParameter('val', $value)
            ->andWhere('t.type = :type')
            ->setParameter('archive', 0)
            ->setParameter('trans', 0)
            ->setParameter('codev', 0)
            ->setParameter('type', 'depot')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
