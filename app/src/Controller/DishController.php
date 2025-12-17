<?php

namespace App\Controller;

use App\Form\DishType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Dish;
use App\Repository\DishRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/admin/dish')]
final class DishController extends AbstractController
{


    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly DishRepository $repository
    ) {}



    #[Route('/', name: 'dish_home', methods: ['GET'])]
    public function index(): Response
    {
        $dishes = $this->repository->findAll();
        return $this->render('dish/index.html.twig', [
            'dishes' => $dishes,
        ]);
    }



    #[Route('/{id}', name: 'dish_show', methods: ['GET'], requirements: ['id' => Requirement::POSITIVE_INT])]
    public function show(Dish $dish): Response
    {
        if (!$dish) {
            throw $this->createNotFoundException('Plat non trouvé');
        }
        return $this->render('dish/show.html.twig', [
            'dish' => $dish,
        ]);
    }


    #[Route('/edit/{id}', name: 'dish_edit', methods: ['GET', 'PATCH'], requirements: ['id' => Requirement::POSITIVE_INT])]
    public function edit(Dish $dish, Request $request): Response
    {
        if (!$dish) {
            throw $this->createNotFoundException('Plat non trouvé');
        }

        $form = $this->createForm(DishType::class, $dish, [
            'method' => 'PATCH',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Plat modifié!');
            return $this->redirectToRoute('dish_home');
        }

        return $this->render('dish/edit.html.twig', [
            'form' => $form,
            'dish' => $dish,
        ]);
    }








    #[Route('/add', name: 'dish_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {

        $dish = new Dish();
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($dish);
            $this->em->flush();
            $this->addFlash('success', 'Plat Ajouté!');
            return $this->redirectToRoute('dish_home');
        }



        return $this->render('dish/add.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'dish_delete', methods: ['DELETE'], requirements: ['id' => Requirement::POSITIVE_INT])]
    public function delete(Dish $dish): Response
    {
        if (!$dish) {
            throw $this->createNotFoundException('Plat non trouvé');
        }
        $this->em->remove($dish);
        $this->em->flush();
        $this->addFlash('success', 'Plat supprimé!');


        return $this->redirectToRoute('dish_home');
    }
}
