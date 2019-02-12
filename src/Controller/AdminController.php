<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\CategoryTreeAdminList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_main_page")
     */
    public function index()
    {
        return $this->render('admin/my_profile.html.twig');
    }


    /**
     * @Route("/categories", name="categories")
     * @param CategoryTreeAdminList $categories
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categories(CategoryTreeAdminList $categories)
    {
        $categories->getCategoryList($categories->buildTree());
        return $this->render('admin/categories.html.twig', [
            'categories' => $categories->categoryList
        ]);
    }

    /**
     * @Route("/edit_category", name="edit_category")
     */
    public function editCategory()
    {

        return $this->render('admin/edit_category.html.twig');
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


    /**
     * @Route("/videos", name="videos")
     */
    public function videos()
    {
        return $this->render('admin/videos.html.twig');
    }

    /**
     * @Route("/upload_video", name="upload_video")
     */
    public function upload_video()
    {
        return $this->render('admin/upload_video.html.twig');
    }

    /**
     * @Route("/admin/users", name="users")
     */
    public function users()
    {
        return $this->render('admin/users.html.twig');
    }
}
