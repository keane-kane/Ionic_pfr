<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Compte;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CompteFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        ($compte = new Compte())
            ->setCode($faker->isbn13)
            ->setMontant($faker->randomNumber())
            ->setCreateAt(new \DateTime())
            ->setArchive(false)
            ->addUser($this->getReference('caissier'))
        ;
        $manager->persist($compte);
        $this->addReference('compte', $compte);
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
