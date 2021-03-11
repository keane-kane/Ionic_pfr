<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Transaction;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TransactionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // $faker = Factory::create('fr_FR');
        // ($transac = new Transaction())
        // ->setCode($faker->isbn13)
        // ->setMontant($faker->randomNumber())
        // ->setCreateAt(new \DateTime())
        // ->setType($faker->randomElement(array('depot', 'retrait')))
        // ->setPartEtat($faker->randomNumber())
        // ->setPartTransfert($faker->randomNumber())
        // ->setPartRetrait(900)
        // ->setPartDepot(600)
        // ->setArchive(false)
        // ->setUserAgenceTransaction($this->getReference('u_agence'))
        // ->setClientTrans($this->getReference('client'))
        // ->setFrais(6666)
        // ;
        // $manager->persist($transac);
        // $this->addReference('transac', $transac);

        // $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
          //  ClientFixtures::class,
        );
     }
}
