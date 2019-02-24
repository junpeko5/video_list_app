<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Video;
use App\Form\UserType;
use App\Repository\VideoRepository;
use App\Utils\CategoryTreeFrontPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class FrontController
 * @package App\Controller
 */
class FrontController extends AbstractController
{
    /**
     * @Route("/", name="main_page")
     */
    public function index()
    {
        return $this->render('front/index.html.twig', [
        ]);
    }


    /**
     * @Route("/video-list/category/{categoryname},{id}/{page}", defaults={"page": "1"}, name="video_list")
     * @param $id
     * @param $page
     * @param CategoryTreeFrontPage $categories
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function videoList($id, $page, CategoryTreeFrontPage $categories, Request $request)
    {
        $categories->getCategoryListAndParent($id);
        $ids = $categories->getChildIds($id);
        array_push($ids, $id);
        $videos = $this->getDoctrine()
            ->getRepository(Video::class)
            ->findByChildIds($ids, $page, $request->get('sortby'));
        return $this->render('./front/video_list.html.twig', [
            'subcategories' => $categories,
            'videos' => $videos,
        ]);
    }


    /**
     * @Route("/video-details/{video}", name="video_details")
     * @param VideoRepository $repo
     * @param $video
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function videoDetails(VideoRepository $repo, $video)
    {
        return $this->render('front/video_details.html.twig', [
            'video' => $repo->videoDetails($video),
        ]);
    }


    /**
     * @Route("/search-results/{page}", methods={"GET"}, defaults={"page": "1"}, name="search_results")
     * @param $page
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchResults($page, Request $request)
    {
        $videos = null;
        $query = null;
        if($query = $request->get('query'))
        {
            $videos = $this->getDoctrine()
                ->getRepository(Video::class)
                ->findByTitle($query, $page, $request->get('sortby'));
            if(!$videos->getItems()) $videos = null;
        }
        return $this->render('front/search_results.html.twig', [
            'videos' => $videos,
            'query' => $query
        ]);
    }


    /**
     * @Route("/pricing", name="pricing")
     */
    public function pricing()
    {
        return $this->render('front/pricing.html.twig', [
        ]);
    }


    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $password_encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $password_encoder)
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setName($request->request->get('user')['name']);
            $user->setLastName($request->request->get('user')['last_name']);
            $user->setEmail($request->request->get('user')['email']);
            $password = $password_encoder->encodePassword($user, $request->request->get('user')['password']['first']);
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->loginUserAutomatically($user, $password);
            return $this->redirectToRoute('admin_main_page');
        }
        return $this->render('front/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $helper
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $helper)
    {

        return $this->render('front/login.html.twig', [
            'error' => $helper->getLastAuthenticationError()
        ]);
    }

    private function loginUserAutomatically($user, $password)
    {
        $token = new UsernamePasswordToken(
            $user,
            $password,
            'main',
            $user->getRoles()
        );
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached');
    }

    /**
     * @Route("/payment", name="payment")
     */
    public function payment()
    {
        return $this->render('front/payment.html.twig', [
        ]);
    }

    /**
     * @Route("/new-comment/{video}", methods={"POST"}, name="new_comment")
     */
    public function newComment(Video $video, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        if (!empty( trim($request->request->get('comment'))))
        {
            $comment = new Comment();
            $comment->setContent($request->request->get('comment'));
            $comment->setUser($this->getUser());
            $comment->setVideo($video);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
        }

        return $this->redirectToRoute('video_details', ['video' => $video->getId()]);
    }

    /**
     * @Route("/video-list/{video}/like", name="like_video", methods={"POST"})
     * @Route("/video-list/{video}/dislike", name="dislike_video", methods={"POST"})
     * @Route("/video-list/{video}/unlike", name="undo_like_video", methods={"POST"})
     * @Route("/video-list/{video}/undodislike", name="undo_dislike_video", methods={"POST"})
     */
    public function toggleLikesAjax(Video $video, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $result = '';
        switch ($request->get('_route'))
        {
            case 'like_video':
                $result = $this->likeVideo($video);
                break;
            case 'dislike_video':
                $result = $this->disLikeVideo($video);
                break;
            case 'undo_like_video':
                $result = $this->undoLikeVideo($video);
                break;
            case 'undo_dislike_video':
                $result = $this->undoDislikeVideo($video);
                break;
        }
        return $this->json([
                'action' => $result,
                'id' => $video->getId()
            ]);
    }

    private function likeVideo($video)
    {
        return 'liked';
    }

    private function disLikeVideo($video)
    {
        return 'disliked';
    }

    private function undoLikeVideo($video)
    {
        return 'undo liked';
    }

    private function undoDislikeVideo($video)
    {
        return 'undo disliked';
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mainCategories()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy(['parent' => null], ['name' => 'ASC']);
        return $this->render('front/_main_categories.html.twig', [
            'categories' => $categories
        ]);
    }

}
