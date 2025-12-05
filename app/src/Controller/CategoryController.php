<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/category')]
final class CategoryController extends AbstractController
{


    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CategoryRepository $repository
    ) {}



    #[Route('/', name: 'category_home', methods: ['GET'])]
    public function index(): Response
    {
        $categories = $this->repository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'category_edit', methods: ['POST', 'GET'], requirements: ['id' => Requirement::POSITIVE_INT])]
    public function edit(Category $category, Request $request): Response
    {
        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }
        $categoryEditForm = $this->createForm(CategoryType::class, $category);
        $categoryEditForm->handleRequest($request);

        if ($categoryEditForm->isSubmitted() && $categoryEditForm->isValid()) {

            $this->em->flush();
            $this->addFlash('success', 'Catégorie modifiée!');
            return $this->redirectToRoute('category_home');
        }
        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'categoryEditForm' => $categoryEditForm
        ]);
    }

    #[Route('/add', name: 'category_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $this->em->persist($category);
            $this->em->flush();
            $this->addFlash('success', 'Catégorie ajoutée!');
            return $this->redirectToRoute('category_home');
        }
        return $this->render('category/add.html.twig', [
            'categoryForm' => $categoryForm
        ]);
    }




    #[Route('/{id}', name: 'category_delete', methods: ['DELETE'], requirements: ['id' => Requirement::POSITIVE_INT])]
    public function delete(Category $category, Request $request): Response
    {
        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }
        if (count($category->getDishes()) > 0) {
            $this->addFlash('error', 'Impossible de supprimer une catégorie qui possède des plats associés.');
            return $this->redirectToRoute('category_home');
        }
        if (!$this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'CSRF détécté.');
            return $this->redirectToRoute('category_home');
        }
        $this->em->remove($category);
        $this->em->flush();
        $this->addFlash('success', 'Catégorie supprimée!');


        return $this->redirectToRoute('category_home');
    }
}
