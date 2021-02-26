<?php

namespace App\DataFixtures;

use App\Entity\Profils;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilsFixtures extends Fixture
{
   

    public const ADMIN_SYS_REFERENCE = 'ADMIN_SYS';
    public const ADMIN_AGENCE_REFERENCE = 'ADMIN_AGENCE';
    public const CAISSIER_REFERENCE = 'CAISSIER';
    public const USER_AGENCE_REFERENCE = 'USER_AGENCE';
    

    public function load(ObjectManager $manager)
    {
            
            $ad_sys = new Profils();
            $ad_sys->setLibelle(self::ADMIN_SYS_REFERENCE);
            $manager->persist($ad_sys);
            
             
            $caissier = new Profils();
            $caissier->setLibelle(self::CAISSIER_REFERENCE);
            $manager->persist($caissier);
            
            $ad_agence = new Profils();
            $ad_agence->setLibelle(self::ADMIN_AGENCE_REFERENCE);
            $manager->persist($ad_agence);

            $user_agence = new Profils();
            $user_agence->setLibelle(self::USER_AGENCE_REFERENCE);
            $manager->persist($user_agence);
    
            
            $this->addReference(self::ADMIN_SYS_REFERENCE, $ad_sys);    
            $this->addReference(self::ADMIN_AGENCE_REFERENCE, $ad_agence);
            $this->addReference(self::CAISSIER_REFERENCE, $caissier); 
            $this->addReference(self::USER_AGENCE_REFERENCE, $user_agence);
            $manager->flush();
    }
}