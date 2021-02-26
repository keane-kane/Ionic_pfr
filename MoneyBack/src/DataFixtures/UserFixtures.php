<?php
namespace App\DataFixtures;

use App\Entity\Cm;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
            $ad_sys = new User();
            $ad_sys->setNom($faker->lastName);
            $ad_sys->setEmail('ads@gmail.com');
            $ad_sys->setProfil($this->getReference(ProfilsFixtures::ADMIN_SYS_REFERENCE));
            $ad_sys->setPrenom($faker->firstName);
            $ad_sys->setPhone($faker->phoneNumber);
            $ad_sys->setArchive(false);
            $password = $this->encoder->encodePassword($ad_sys, ProfilsFixtures::ADMIN_SYS_REFERENCE);
            $ad_sys->setPassword($password);
            $manager->persist($ad_sys);

            $caissier = new User();
            $caissier->setNom($faker->lastName);
            $caissier->setEmail('cassier@gmail.com');
            $caissier->setProfil($this->getReference(ProfilsFixtures::CAISSIER_REFERENCE));
            $caissier->setPrenom($faker->firstName);
            $caissier->setPhone($faker->phoneNumber);
            $caissier->setArchive(false);
            $password = $this->encoder->encodePassword($caissier, ProfilsFixtures::CAISSIER_REFERENCE);
            $caissier->setPassword($password);
            $manager->persist($caissier);

            $ad_agence = new User();
            $ad_agence->setNom($faker->lastName);
            $ad_agence->setEmail('agence@gmail.com');
            $ad_agence->setProfil($this->getReference(ProfilsFixtures::ADMIN_AGENCE_REFERENCE));
            $ad_agence->setPrenom($faker->firstName);
            $ad_agence->setPhone($faker->phoneNumber);
            $ad_agence->setArchive(false);
            $password = $this->encoder->encodePassword($ad_agence, ProfilsFixtures::ADMIN_AGENCE_REFERENCE);
            $ad_agence->setPassword($password);
            $manager->persist($ad_agence);

            $u_agence = new User();
            $u_agence->setNom($faker->lastName);
            $u_agence->setEmail('user@gmail.com');
            $u_agence->setProfil($this->getReference(ProfilsFixtures::USER_AGENCE_REFERENCE));
            $u_agence->setPrenom($faker->firstName);
            $u_agence->setPhone($faker->phoneNumber);
            $u_agence->setArchive(false);
            $password = $this->encoder->encodePassword($u_agence, ProfilsFixtures::USER_AGENCE_REFERENCE);
            $u_agence->setPassword($password);
            $manager->persist($u_agence);

            $this->addReference('ad_sys', $ad_sys);    
            $this->addReference('ad_agence', $ad_agence);
            $this->addReference('caissier', $caissier); 
            $this->addReference('u_agence', $u_agence);
            $manager->flush();
              
        
    }
    public function getDependencies()
    {
        return array(
            ProfilsFixtures::class,
        );
    }
    
}