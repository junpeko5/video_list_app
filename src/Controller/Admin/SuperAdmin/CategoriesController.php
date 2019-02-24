<?php

namespace App\Controller\Admin\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Utils\CategoryTreeAdminList;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/admin/su")
 * Class CategoriesController
 * @package App\Controller\Admin\SuperAdmin
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("/categories", name="categories", methods={"GET", "POST"})
     * @param CategoryTreeAdminList $categories
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function categories(CategoryTreeAdminList $categories, Request $request)
    {
        $categories->getCategoryList($categories->buildTree());
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $is_invalid = null;

        if($this->saveCategory($category, $form, $request))
        {
            return $this->redirectToRoute('categories');
        }
        elseif($request->isMethod('post'))
        {
            $is_invalid = ' is-invalid';
        }
        return $this->render('admin/categories.html.twig', [
            'categories' => $categories->categoryList,
            'form' => $form->createView(),
            'is_invalid' => $is_invalid,
        ]);
    }


    /**
     * @Route("/edit-category/{id}", name="edit_category", methods={"GET", "POST"})
     * @param Category $category
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editCategory(Category $category, Request $request)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $is_invalid = null;

        if($this->saveCategory($category, $form, $request))
        {
            return $this->redirectToRoute('categories');
        }
        elseif($request->isMethod('post'))
        {
            $is_invalid = ' is-invalid';
        }

        return $this->render('admin/edit_category.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'is_invalid' => $is_invalid
        ]);
    }


    /**
     * @Route("/delete-category/{id}", name="delete_category")
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCategory(Category $category)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirectToRoute('categories');
    }

    private function saveCategory(Category $category, $form, Request $request)
    {
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $category->setName($request->request->get('category')['name']);
            $repository = $this->getDoctrine()->getRepository(Category::class);
            $parent = $repository->find($request->request->get('category')['parent']);
            $category->setParent($parent);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return true;
        }
        return false;
    }
}
