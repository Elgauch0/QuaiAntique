<?php

namespace App\Controller;

use App\Form\DishType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Dish;

final class DishController extends AbstractController
{
    #[Route('/dish/add', name: 'app_dish')]
    public function index(): Response
    {
        $dish = new Dish();
        $form = $this->createForm(DishType::class, $dish);


        return $this->render('dish/add.html.twig', [
            'form' => $form,
        ]);
    }
}
