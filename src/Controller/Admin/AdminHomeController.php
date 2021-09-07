<?php


namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AdminHomeController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     * @return Response
     */
    public function index(UserRepository $userRepository, Request $request, Security $security)
    {
        $page = $request->query->get('page', 1);
        return $this->render('admin/index.html.twig', [
            'self_id' => $security->getUser()->getId(),
            'users' => $userRepository->findLatest($page),
            'roles' => User::USER_ROLES
        ]);
    }
}
