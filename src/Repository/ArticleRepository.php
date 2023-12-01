<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function archive(Article $article): self
    {
        $article->setArchivedAt(new \DateTimeImmutable());
        $this->getEntityManager()->flush();
        return $this;
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findArchived($search = null): array
    {
        return $this->findSearch($search)
            ->andWhere('a.archived_at IS NOT NULL')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findNotArchived($search = null): array
    {
        return $this->findSearch($search)
            ->andWhere('a.archived_at IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function findSearch($search = null): \Doctrine\ORM\QueryBuilder
    {
        $q = $this->createQueryBuilder('a');
        if (!$search) return $q;
        return $q->andWhere("a.body LIKE :var")
            ->setParameter('var', '%'.$search.'%');
    }


//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
