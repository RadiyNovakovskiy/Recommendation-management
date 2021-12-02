<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Service\Post\PostService;
use App\Form\PostType;
use App\Entity\Post;

class PostController extends BaseController
{
    /**
     * @var PostService
     */
    private $postService;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;

    public function __construct(PostService $postService, UserRepositoryInterface $userRepository, PostRepositoryInterface $postRepository, CommentRepositoryInterface $commentRepository)
    {
        $this->postService = $postService;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository; 
    }

    /**
     * @Route("/main/post", name="post")
     */
    public function index(): Response
    {
        $forRender = parent::renderDefault();
        return $this->render('main/post/index.html.twig', $forRender);
    }

    /**
     * @Route("/main/post/create/{userId}", name="post_create")
     * @param Request $request
     * @param int $userId
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, int $userId)
    {
        $post = new Post();
        $user =$this->userRepository->getOne($userId);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->postService->handleCreate($form, $post, $user);
            $this->addFlash('success', 'Отзыв успешно добавлен!');
            return $this->redirectToRoute("home");
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание отзыва';
        $forRender['form'] = $form->createView();
        return $this->render('main/post/form.html.twig', $forRender);
    }

    /**
     * @Route("/main/post/update/{userId}/{postId}", name="post_update")
     * @param Request $request
     * @param int $userId
     * @param int $postId
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, int $userId, int $postId)
    {
        $post = $this->postRepository->getOne($postId);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($form->get('save')->isClicked())
            {
                $this->postService->handleUpdate($form, $post);
                $this->addFlash('success', 'Пост успешно отредактирован!');
            }
            if ($form->get('delete')->isClicked())
            {
                $this->postService->handleUpdate($form, $post);
                $this->addFlash('success', 'Пост успешно удален!');
            }
            return $this->redirectToRoute('user_profile', ['userId' => $userId]);
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Редактирование отзыва';
        $forRender['form'] = $form->createView();
        return $this->render('main/post/form.html.twig', $forRender);
    }

    /**
     * @Route("/main/post/view/{postId}", name="post_view")
     * @param int $postId
     */
    public function view(string $postId): Response
    {
        $forRender = parent::renderDefault();
        $forRender['post'] = $this->postRepository->getOne($postId);
        $forRender['comments'] = $this->commentRepository->getAll();
        return $this->render('main/post/view.html.twig', $forRender);
    }
}
