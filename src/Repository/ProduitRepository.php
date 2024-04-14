<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function rechercherInscriptionsParNomProduit($libelle): array
    {
        // Utilisation du EntityManager pour accéder au repository d'Inscription
        $inscriptionsRepository = $this->getEntityManager()->getRepository(Inscription::class);

        // Utilisation de la méthode du repository d'Inscription pour rechercher les inscriptions
        return $inscriptionsRepository->createQueryBuilder('i')
            ->join('i.formation', 'f')
            ->join('i.employe', 'e')
            ->join('f.produit', 'p') // Assurez-vous que la relation entre Formation et Produit est correcte dans votre modèle
            ->andWhere('p.libelle = :libelle')
            ->setParameter('libelle', $libelle)
            ->getQuery()
            ->getResult();
    }

public function findByLibelleProduit($libelle): array
{
    return $this->findBy(['libelle' => $libelle]);
}


public function findOneByLibelleProduit($libelle): ?Produit
{
    return $this->findOneBy(['libelle' => $libelle]);
}


//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
