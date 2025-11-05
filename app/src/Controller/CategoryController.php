<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Category;
use App\Form\CategoryType;

final class CategoryController extends AbstractController
{
    #[Route('/category/{id}', name: 'category.edit', requirements: ['id' => '\d+'])]
    public function index(Category $category): Response
    {

        $categoryEditForm = $this->createForm(CategoryType::class,$category);



        return $this->render('category/edit.html.twig', [
            'category'=> $category,
            'categoryEditForm'=> $categoryEditForm
        ]);
    }


}
