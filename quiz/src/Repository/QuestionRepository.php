<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function findQuestions(int $id)
    {
        // return $this -> createQueryBuilder('question')
        // -> innerJoin('question.id_categorie', 'categorie.id')
        // -> where('question.id_categorie = :question.id_categorie')
        // -> setParameter('question.id_categorie', $id)
        // -> getQuery() -> getResult();
        $connect = $this -> getEntityManager() -> getConnection();
        $query = 'SELECT question.id, question, id_categorie AS id_quizz, categorie.name FROM question INNER JOIN categorie ON question.id_categorie = categorie.id WHERE id_categorie = ?';
        $prepare = $connect -> prepare($query);
        $result = $prepare -> executeQuery([$id]);
        return $result -> fetchAllAssociative();
    }

    public function findAnswers(int $id)
    {
        $connect = $this -> getEntityManager() -> getConnection();
        $query = 'SELECT reponse.id, reponse, id_question, reponse_expected FROM reponse INNER JOIN question ON reponse.id_question = question.id WHERE id_question = ?';
        $prepare = $connect -> prepare($query);
        $result = $prepare -> executeQuery([$id]);
        return $result -> fetchAllAssociative();
    }

    public function findCategorie(int $id)
    {
        $connect = $this -> getEntityManager() -> getConnection();
        $query = 'SELECT name AS title, id AS id_categorie FROM true_categorie WHERE id = ?';
        $prepare = $connect -> prepare($query);
        $result = $prepare -> executeQuery([$id]);
        return $result -> fetchAllAssociative();
    }

    public function findGoodAnswer(int $id)
    {
        $connect = $this -> getEntityManager() -> getConnection();
        $query = 'SELECT id FROM reponse WHERE reponse_expected = 1 AND id_question = ?';
        $prepare = $connect -> prepare($query);
        $result = $prepare -> executeQuery([$id]);
        return $result -> fetchOne();
    }

    public function findResponse(int $id)
    {
        $connect = $this -> getEntityManager() -> getConnection();
        $query = 'SELECT reponse FROM reponse WHERE id = ?';
        $prepare = $connect -> prepare($query);
        $result = $prepare -> executeQuery([$id]);
        return $result -> fetchOne();
    }
    //    /**
    //     * @return Question[] Returns an array of Question objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Question
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
