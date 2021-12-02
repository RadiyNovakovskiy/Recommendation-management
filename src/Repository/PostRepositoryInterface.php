<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

interface PostRepositoryInterface
{
    /**
     * @param Post $post
     * @return Post
     */
    public function setCreate(Post $post): object;

    /**
     * @param Post $post
     * @return Post
     */
    public function setSave(Post $post): object;

    /**
     * @param Post $post
     */
    public function setDelete(Post $post);

    /**
     * @param int $postId
     * @return Post|null
     */
    public function getOne(int $postId): ?object;

    /**
     * @return Post[]
     */
    public function getAll(): array;

    /**
     * @param int $userId
     * @return Post[]|null 
     */
    public function getUserPost(int $postId): ?array;

}