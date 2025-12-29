<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\GalleryType;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class GallerieController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ImageRepository $repository
    ) {}




    #[Route('/gallerie', name: 'app_gallerie', methods: ['GET'])]
    public function index(): Response
    {
        $images = $this->repository->findAll();

        return $this->render('gallerie/index.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/admin/gallerie/add', name: 'app_gallerie_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $image = new Image();
        $form = $this->createForm(GalleryType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($image);
            $this->em->flush();

            return $this->redirectToRoute('app_gallerie');
        }

        return $this->render('gallerie/add.html.twig', [
            'form' => $form,
        ]);
    }




    #[Route('/admin/gallerie/delete/{id}', name: 'app_gallerie_delete', methods: ['POST'])]
    public function delete(Image $image, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $request->request->get('_token'))) {
            $this->em->remove($image);
            $this->em->flush();
            $this->addFlash('success', 'Image deleted successfully.');
            return $this->redirectToRoute('app_gallerie');
        }
        $this->addFlash('error', 'Invalid CSRF token.');
        return $this->redirectToRoute('app_gallerie');
    }
}
