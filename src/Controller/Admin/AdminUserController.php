<?php

namespace App\Controller\Admin;


use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class AdminUserController extends AbstractController
{
    const ROLES_LABELS = [
        'ROLE_ADMIN' => "Администратор",
        'ROLE_USER' => "Пользователь",
    ];

    /**
     * @Route("/admin/user", name="admin_user")
     * @return Response
     */
    public function index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $forRender['title'] = 'Пользователи';
        $forRender['users'] = $users;
        return $this->render('admin/user/index.html.twig', $forRender);
    }

        /**
         * @Route("/admin/user/create", name="admin_user_create")
         * @param Request $request
         * @param UserPasswordEncoderInterface $passwordEncoder
         * @return RedirectResponse|Response
         */

    public function create(Security $security, Request $request, UserPasswordEncoderInterface $passwordEncoder, UserPasswordEncoderInterface $encoder)
       {

           $user = new User();
           $form = $this->createForm(UserType::class, $user, [
               'roles' => self::ROLES_LABELS,
               'is_creating' => true
           ]);
           $em = $this->getDoctrine()->getManager();
           $form->handleRequest($request);

           if(($form->isSubmitted()) && ($form->isValid()))
           {
               $data = $request->request->all();
               $encoded = $encoder->encodePassword($user, $data['user']['password']);
               $user->setPassword($encoded);
               $em->persist($user);
               $em->flush();

               return $this->redirectToRoute('admin_home');
           }

           $forRender['title'] = 'Форма создания пользователя';
           $forRender['form'] = $form->createView();
           return $this->render('user/form.html.twig', $forRender);

       }

    /**
     * @Route("/admin/user/{id}/edit", name="user_edit", methods="GET|POST", requirements={"id"="\d+"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder, Security $security): Response
    {
        $full_granted = !(!$security->isGranted('ROLE_ADMIN') && (in_array("ROLE_ADMIN", $user->getRoles()) || in_array("ROLE_ADMIN", $user->getRoles())));
        if (!$full_granted) {
            throw new AccessDeniedHttpException("Access Denied.");
        }
        $em = $this->getDoctrine()->getManager();
        $rolesBefore = $user->getRoles();
        $passwordBefore = $user->getPassword();
        $form = $this->createForm(UserType::class, $user, [
            'roles' => self::ROLES_LABELS,
        ]);
        $form->handleRequest($request);
        if (empty($user->getPassword())) {
            $user->setPassword($passwordBefore);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->all(); // In this case, we can not use: $data = $form->getData();

            if ($user->getPassword() !== $passwordBefore) {
                $encoded = $encoder->encodePassword($user, $data['user']['password']);
                $user->setPassword($encoded);
                $em->persist($user);
            }

            if (!$full_granted) {
                $user->setRoles($rolesBefore);
            }

            $em->flush();
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('user/change.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'full_granted' => $full_granted && $user->getId() !== $security->getUser()->getId(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="user_delete", methods="DELETE", requirements={"id"="\d+"})
     */
    public function delete(Request $request, User $user, Security $security): Response
    {
        $full_granted = !(!$security->isGranted('ROLE_ADMIN') && (in_array("ROLE_ADMIN", $user->getRoles()) || in_array("ROLE_ADMIN", $user->getRoles())));
        if (!$full_granted) {
            throw new AccessDeniedHttpException("Access Denied.");
        }

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_home');
    }
}
