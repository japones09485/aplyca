<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function BuscarAllPost(){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('post.id,post.titulo,post.descripcion, post.contenido, post.foto, post.fecha_publicacion,post.likes,user.nombre')
            ->from('App\Entity\Post', 'post')
            ->leftJoin('post.usuario', 'user')
            ->groupBy('post.id')
        ;
        
        return $this->getEntityManager()
        ->createQuery(
            $qb
        )->getResult();
    }

    public function PostUsu($id){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('post.id,post.titulo,post.descripcion, post.contenido, post.foto, post.fecha_publicacion,post.likes,user.nombre')
            ->from('App\Entity\Post', 'post')
            ->leftJoin('post.usuario', 'user')
            ->where('post.usuario = '.$id.'')
            ->groupBy('post.id')
        ;
        
        return $this->getEntityManager()
        ->createQuery(
            $qb
        )->getResult();
    }

    public function PostId($id){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('post.id,post.titulo,post.descripcion, post.contenido, post.foto, post.fecha_publicacion,post.likes,user.nombre')
            ->from('App\Entity\Post', 'post')
            ->leftJoin('post.usuario', 'user')
            ->where('post.id = '.$id.'')
            ->groupBy('post.id')
        ;
        
        return $this->getEntityManager()
        ->createQuery(
            $qb
        )->getResult();
    }


    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
