<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $user = new Utilisateur();
        $user->setEmail('paul@paul.fr');
        $user->setRoles((array)'ROLE_ENSEIGNANT');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'wick'));
        $user->

        $manager->persist($user);
        $user2 = new Utilisateur();
        $user2->setEmail('john@john.fr');
        $user2->setRoles((array)'ROLE_ENSEIGNANT');
        $user2->setPassword($this->passwordEncoder->encodePassword($user2, 'john'));
        $manager->persist($user2);
        $manager->flush();



        $manager->persist($user);
        $user3 = new Utilisateur();
        $user3->setEmail('lea@lea.fr');
        $user3->setRoles((array)'ROLE_ETUDIANT');
        $user3->setPassword($this->passwordEncoder->encodePassword($user2, 'lea'));
        $manager->persist($user3);
        $manager->flush();
    }
}
