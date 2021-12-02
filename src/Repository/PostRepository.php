<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileManagerServiceInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, Post::class);
    }


    public function setCreate(Post $post): object
    {
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

    public function setSave(Post $post): object
    {
        $this->entityManager->flush();

        return $post;
    }

    public function setDelete(Post $post)
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    public function getOne(int $postId): ?object
    {
        return parent::find($postId);
    }

    public function getAll(): array
    {
        return parent::findAll();
    }

    public function getUserPost(int $userId): ?array 
    {
        return parent::findBy(array('user' => $userId));
    }
}
