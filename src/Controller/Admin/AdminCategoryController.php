<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminBaseController;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\CategoryRepositoryInterface;
use App\Service\Category\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;


class AdminCategoryController extends AdminBaseController
{
    /**
     *@var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     *@var CategoryService
     */
    private $categoryService;

    public function __construct(CategoryRepositoryInterface $categoryRepository, CategoryService $categoryService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryService = $categoryService;
    }
    /**
     * @Route("/admin/category", name="admin_category")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Categories';
        $forRender['category'] = $this->categoryRepository->getAll();
        return $this->render('admin/category/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/category/create", name="admin_category_create")
     * @param Request $request
     * @return RedirectResponse/Response
     */
    public function createAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->categoryService->handleCreate($category);
            $this->addFlash('success', 'Category was added!');
            return $this->redirectToRoute('admin_category');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Creating category';
        $forRender['form'] = $form->createView();
        return $this->render('admin/category/form.html.twig', $forRender);
    }


    /**
     * @Route("/admin/category/update/{categoryId}", name="admin_category_update")
     * @param int $categoryId
     * @param Request $request
     * @return RedirectResponse/Response
     */
    public function updateAction(int $categoryId, Request $request)
    {
        $category = $this->categoryRepository->getOne($categoryId);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            if($form->get('save')->isClicked())
            {
                $this->categoryService->handleUpdate($category);
                $this->addFlash('success', 'Category was updated!');
            }
            if($form->get('delete')->isClicked())
            {
                $this->categoryService->handleDelete($category);
                $this->addFlash('success', 'Category was deleted!');
            }
            return $this->redirectToRoute('admin_category');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Updating category';
        $forRender['form'] = $form->createView();
        return $this->render('admin/category/form.html.twig', $forRender);
    }
}