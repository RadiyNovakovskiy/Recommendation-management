<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

interface CommentRepositoryInterface 
{
    /**
     * @param Comment $comment
     * @return Comment
     */
    public function setCreate(Comment $comment): object;

    /**
     * @param Comment $comment
     * @return Comment
     */
    public function setUpdate(Comment $comment): object;
    
    /**
     * @param Comment $comment
     */
    public function setDelete(Comment $comment);

    /**
     * @return Comment[]
     */
    public function getAll(): array;
}