<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    { 
        // $faker = Factory::create('fr_FR');
        // ($client = new Client())
        //     ->setNomClient($faker->firstName)
        //     ->setNomBeneficiaire($faker->firstName)
        //     ->setCniClient($faker->isbn10)
        //     ->setCniBeneficiaire($faker->isbn10)
        //     ->setPhoneClient($faker->phoneNumber)
        //     ->setPhoneBeneficiaire($faker->phoneNumber)
        //     ->setArchive(false)
        //     ->setFaire($this->getReference('transac'))
        // ;
        // $manager->persist($client);

        // $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            TransactionFixtures::class,
        );
    }
}
