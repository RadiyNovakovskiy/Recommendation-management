<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PostRepositoryInterface;
use App\Repository\CommentRepositoryInterface;

class HomeController extends BaseController
{
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;

    public function __construct(PostRepositoryInterface $postRepository, CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        $forRender['posts'] = $this->postRepository->getAll();
        $forRender['comments'] = $this->commentRepository->getAll();
        return $this->render('main/index.html.twig', $forRender);
    }

    /**
     * @Route("/profile/{userId}", name="user_profile")
     * @param int $userId
     */
    public function profile(int $userId)
    {
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Профиль пользователя';
        $forRender['posts'] = $this->postRepository->getUserPost($userId);
        $forRender['comments'] = $this->commentRepository->getAll();
        return $this->render('main/profile.html.twig', $forRender);
    }
}