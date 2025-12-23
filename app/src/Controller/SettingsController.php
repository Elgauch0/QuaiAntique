<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\SettingsType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\RestaurantSettings;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin')]
final class SettingsController extends AbstractController
{


    public function __construct(private readonly EntityManagerInterface $em) {}




    #[Route('/settings', name: 'app_settings', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $settings = $this->em->getRepository(RestaurantSettings::class)->findOneBy([]) ?? new RestaurantSettings();
        $form = $this->createForm(SettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($settings);
            $this->em->flush();
            $this->addFlash('success', 'Les paramètres ont été mis à jour avec succès.');
            return $this->redirectToRoute('app_settings');
        }

        return $this->render('settings/index.html.twig', [
            'settingsForm' => $form,
        ]);
    }
}
