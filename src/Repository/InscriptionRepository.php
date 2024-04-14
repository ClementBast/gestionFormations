<?php

namespace App\Repository;


use App\Entity\Inscription;
use App\Entity\Employe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inscription>
 *
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }
    public function rechercherInscriptionsParNomEtPrenom($nom, $prenom): array
    {
        return $this->createQueryBuilder('i')
        ->join('i.employe', 'e')
        ->andWhere('e.nom = :nom')
        ->andWhere('e.prenom = :prenom')
        ->andWhere('i.statut = :statut')
        ->setParameter('nom', $nom)
        ->setParameter('prenom', $prenom)
        ->setParameter('statut', 'Acceptee')
        ->getQuery()
        ->getResult();
    }

//    /**
//     * @return Inscription[] Returns an array of Inscription objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Inscription
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
?>