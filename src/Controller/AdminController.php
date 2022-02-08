<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/profile/{id}", name="admin_profile")
     */
    public function profile(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if ( $user->getId() === $this->getUser()->getId() ) {
            return $this->redirectToRoute('profile');
        }

        $form = $this->createForm(AdminProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
        }

        return $this->render('admin/admin-profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
