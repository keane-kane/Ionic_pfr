<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Agence;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AgenceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        ($agence = new Agence())
            ->setNom('ODC')
            ->setAdresse($faker->address)
            ->setArchive(false)
            ->setPhone($faker->phoneNumber)
            ->setAppartient($this->getReference('compte'))
            ->setAdminsystem($this->getReference('ad_sys'))
            ->setAdminagence($this->getReference('ad_agence'))
            ;

        $manager->persist($agence);
        $this->addReference('agence', $agence);
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            CompteFixtures::class,
        );
    }
}
