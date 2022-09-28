<?php

namespace App\Repository;

use App\Entity\NiveauFiliere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NiveauFiliere|null find($id, $lockMode = null, $lockVersion = null)
 * @method NiveauFiliere|null findOneBy(array $criteria, array $orderBy = null)
 * @method NiveauFiliere[]    findAll()
 * @method NiveauFiliere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NiveauFiliereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NiveauFiliere::class);
    }

}
