<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\Category;
use App\Form\PostType;
use App\Service\FileManagerServiceInterface;
use App\Service\Post\PostService;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\CommentRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminPostController extends AdminBaseController
{
    /**
     * @var PostService
     */
    private $postService;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;


    public function __construct(PostService $postService, CategoryRepositoryInterface $categoryRepository, PostRepositoryInterface $postRepository, CommentRepositoryInterface $commentRepository)
    {
        $this->postService = $postService;
        $this->categoryRepository = $categoryRepository;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Posts';
        $forRender['category'] = $this->categoryRepository->getAll();
        $forRender['comment'] = $this->commentRepository->getAll();
        return $this->render('admin/post/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/post/create", name="admin_post_create")
     * @param Request $request
     * @return RedirectResponse/Response
     */
    public function createAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->postService->handleCreate($form, $post);
            $this->addFlash('success', 'Post was added!');
            return $this->redirectToRoute('admin_post');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Creating post';
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig', $forRender);
    }

    /**
     * @Route("/admin/post/update/{postId}", name="admin_post_update")
     * @param int $postId
     * @param Request $request
     * @return RedirectResponse/Response
     */
    public function updateAction(int $postId, Request $request)
    {
        $post = $this->postRepository->getOne($postId);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if($form->get('save')->isClicked())
            {
                $this->postService->handleUpdate($form, $post);
                $this->addFlash('success', 'Post was updated!');
            }
            if($form->get('delete')->isClicked())
            {
                $this->postService->handleDelete($post);
                $this->addFlash('success', 'Post was deleted!');
            }
            return $this->redirectToRoute('admin_post');
        }
        
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Updating post';
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig', $forRender);
    }
}