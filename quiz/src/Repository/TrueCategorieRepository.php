<?php

namespace App\Repository;

use App\Entity\TrueCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrueCategorie>
 */
class TrueCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrueCategorie::class);
    }

    public function findQuizz(int $id)
    {
        $connect = $this -> getEntityManager() -> getConnection();
        $query = 'SELECT id_categorie_id, categorie.name, categorie.id, true_categorie.name AS "title" FROM categorie INNER JOIN true_categorie ON categorie.id_categorie_id = true_categorie.id WHERE id_categorie_id = ?';
        $prepare = $connect -> prepare($query);
        $result = $prepare -> executeQuery([$id]);
        return $result -> fetchAllAssociative();
    }
    //    /**
    //     * @return TrueCategorie[] Returns an array of TrueCategorie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TrueCategorie
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
