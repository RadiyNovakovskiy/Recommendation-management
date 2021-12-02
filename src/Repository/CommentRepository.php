<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Statement;
use App\Service\FileManagerServiceInterface;


/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository implements CommentRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, Comment::class);
    }

    public function setCreate(Comment $comment): object
    {
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
        
        return $comment;
    }

    public function setUpdate(Comment $comment): object
    {
        $this->entityManager->flush();

        return $comment;
    }

    public function setDelete(Comment $comment)
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }

    public function getOne(): object
    {
        $query = $this->entityManager->createQuery('SELECT (MAX(comment.create_at)) FROM App\Entity\Comment comment GROUP BY comment.post');
        return $query->getResult();
    }

    public function getAll(): array
    {
        return parent::findAll();
    }
}
