<?php

namespace App\Service\Post;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepositoryInterface;
use App\Service\FileManagerServiceInterface;
use Symfony\Component\Form\FormInterface;


class PostService 
{
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var FileManagerServiceInterface
     */
    private $fileManager;

    public function __construct(PostRepositoryInterface $postRepository, FileManagerServiceInterface $fileManager)
    {
        $this->postRepository = $postRepository;
        $this->fileManager = $fileManager;
    }

    /**
     * @param FormInterface $form
     * @param Post $post
     * @param User $user
     * @return PostService
     */
    public function handleCreate(FormInterface $form, Post $post, User $user)
    {
        $image = $form->get('image')->getData();
        $post->setCreateAtValue();
        $post->setUpdateAtValue();
        $post->setIsPublished();
        $post->setUser($user);

        if ($image)
        {
            $fileName = $this->fileManager->imagePostUpload($image);
            $post->setImage($fileName);
        }

        $this->postRepository->setCreate($post);
        return $this;
    }

    /**
     * @param FormInterface $form
     * @param Post $post
     * @return PostService
     */
    public function handleUpdate(FormInterface $form, Post $post)
    {
        $image = $form->get('image')->getData();

        if ($image)
        {
            $imageOld = $post->getImage();
            if ($imageOld)
            {
                $this->fileManager->removePostImage($imageOld);
            }
            $fileName = $this->fileManager->imagePostUpload($image);
            $post->setImage($fileName);
        }
        $post->setUpdateAtValue();
        $this->postRepository->setSave($post);
        return $this;
    }

    /**
     * @param Post $post
     */
    public function handleDelete(Post $post)
    {
        $image = $post->getImage();
        if ($image)
        {
            $this->fileManager->removePostImage($image);
        }
        $this->postRepository->setDelete($post);

    }
}