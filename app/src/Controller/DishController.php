<?php

namespace App\Controller;

use App\Form\DishType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Dish;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class DishController extends AbstractController
{


    public function __construct(private readonly  EntityManagerInterface $em) {}



    #[Route('/dish', name: 'dish_home', methods: ['GET'])]
    public function index(): Response
    {
        $dishes = $this->em->getRepository(Dish::class)->findAll();
        return $this->render('dish/index.html.twig', [
            'dishes' => $dishes,
        ]);
    }









    #[Route('/dish/add', name: 'dish_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {

        $dish = new Dish();
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($dish);
            $this->em->flush();
            $this->addFlash('success', 'Plat Ajouté!');
            return $this->redirectToRoute('dish_add');
        }



        return $this->render('dish/add.html.twig', [
            'form' => $form,
        ]);
    }
}
