<?php

namespace App\Service\Comment;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CommentRepositoryInterface;
use App\Service\FileManagerServiceInterface;

class CommentService
{
    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository) 
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param User $user
     * @param Post $post
     * @param Comment $comment
     * @return CommentService
     */
    public function handleCreate(USer $user, Post $post, Comment $comment)
    {
        $comment->setCreateAtValue();
        $comment->setIsPublished();
        $comment->setUser($user);
        $comment->setPost($post);

        $this->commentRepository->setCreate($comment);
        return $this;
    }

    /**
     * @param Comment $comment
     * @return CommentService
     */
    public function handleUpdate(Comment $comment)
    {
        $this->commentRepository->setSave($comment);
        return $this;
    }

    /**
     * @param Comment $comment
     */
    public function handleDelete(Comment $comment)
    {
        $this->commentRepository->setDelete($comment);
    }
}