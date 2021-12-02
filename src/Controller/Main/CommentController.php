<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\CommentRepositoryInterface;
use App\Form\CommentType;
use App\Entity\Comment;
use App\Entity\Post;
use App\Service\Comment\CommentService;

class CommentController extends BaseController
{
    /**
     * @var CommentService
     */
    private $commentService;

    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(CommentService $commentService, PostRepositoryInterface $postRepository, CommentRepositoryInterface $commentRepository, UserRepositoryInterface $userRepository)
    {
        $this->commentService = $commentService;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/main/comment", name="main_comment")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Комментарии';
        $forRender['comments'] = $this->commentRepository->getAll();
        return $this->render('main/comment/index.html.twig', $forRender);
    }

    /**
     * @Route("/main/comment/create/{userId}/{postId}", name="main_comment_create")
     * @param Request $request
     * @param int $userId
     * @param int $postId
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, int $userId, int $postId)
    {
        $user = $this->userRepository->getOne($userId);
        $post = $this->postRepository->getOne($postId);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->commentService->handleCreate($user, $post, $comment);
            $this->addFlash('success', 'Комментарий успешно добавлен!');
            return $this->RedirectToRoute('home');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Напишите свой отзыв';
        $forRender['form'] = $form->createView();
        return $this->render('main/comment/form.html.twig', $forRender);
    }
}
