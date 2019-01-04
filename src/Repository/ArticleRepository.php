<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllPostedAfter($datePost) {
        // on récupère l'équivalent de l'objet de connexion à PDO que l'on utilisait
        $connexion = $this->getEntityManager()->getConnection();
        // on stocke la requête dans une variable
        $sql = 'SELECT * FROM article WHERE date_publi > :datePost ORDER BY date_publi DESC';
        $select = $connexion->prepare($sql);
        $select->bindValue(':datePost', $datePost);
        $select->execute();
        // je renvoie un tableau de tableaux d'articles
        return $select->fetchAll();
    }

    // même méthode en objet
    public function findAllPostedAfter2($datePost) {
        $queryBuilder = $this->createQueryBuilder('a')
            ->innerJoin('a.user', 'u')
            ->addSelect('u')
            ->andWhere('a.date_publi > :datePost')
            ->setParameter('datePost', $datePost)
            ->orderBy('a.date_publi', 'DESC')
            ->getQuery();
        return $queryBuilder->execute();
    }

    // méthode qui va me permettre de récupérer ma liste d'articles et mes utilisateurs
    // en une sule requête, en faisant une jointure
    public function myFindAll() {
        $queryBuilder = $this->createQueryBuilder('a')
            // je fais la jointure
            // a.user représente la propriété user de mon entité article
            ->innerJoin('a.user', 'u')
            // on récupère ici les données de l'utilisateur associé à l'article
            ->addSelect('u')
            ->orderBy('a.date_publi', 'DESC')
            ->getQuery();
        return $queryBuilder->execute();    
    }
}
