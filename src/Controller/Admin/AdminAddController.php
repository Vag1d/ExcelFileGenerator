<?php

namespace App\Controller\Admin;


use App\Entity\Add;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Security;


class AdminAddController extends AbstractController
{
  /**
 * @Route("/admin/user/new", name="admin_user_new", methods="GET|POST")
 * @return RedirectResponse|Response
 */

 public function index(Request $request, Security $security)
    {


        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        }
    return $this->render('user/add.html.twig', array(
        'form' => $form->createView(),
    ));

}
}
