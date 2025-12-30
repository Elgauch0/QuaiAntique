<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ProfilType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/user')]
final class ProfilController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {}




    #[Route('/', name: 'app_profil')]
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        return $this->render('profil/index.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/edit', name: 'app_profil_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request): Response
    {
        $ProfilForm = $this->createForm(ProfilType::class, $this->getUser());

        $ProfilForm->handleRequest($request);
        if ($ProfilForm->isSubmitted() && $ProfilForm->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès !');
            return $this->redirectToRoute('app_profil');
        }
        return $this->render('profil/edit.html.twig', [
            'ProfilForm' => $ProfilForm,
        ]);
    }



    #[Route('/delete', name: 'app_profil_delete', methods: ['DELETE'])]
    public function delete(Request $request, TokenStorageInterface $tokenStorage): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Utilisateur non authentifié.');
        }
        if ($this->isCsrfTokenValid('delete-profile' . $user->getId(), $request->request->get('_token'))) {
            $tokenStorage->setToken(null);
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', 'Votre compte a été supprimé avec succès.');

            return $this->redirectToRoute('app_home');
        }


        return $this->redirectToRoute('app_logout');
    }
}
