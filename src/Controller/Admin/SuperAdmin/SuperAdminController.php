<?php

namespace App\Controller\Admin\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/su")
 * Class SuperAdminController
 * @package App\Controller\Admin\SuperAdmin
 */
class SuperAdminController extends AbstractController
{
    /**
     * @Route("/upload-video", name="upload_video")
     */
    public function upload_video()
    {
        return $this->render('admin/upload_video.html.twig');
    }

    /**
     * @Route("/users", name="users")
     */
    public function users()
    {
        return $this->render('admin/users.html.twig');
    }
}
