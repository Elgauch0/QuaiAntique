<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Form\BookingType;
use App\Entity\Booking;
use App\Entity\RestaurantSettings;
use App\Repository\BookingRepository;
use App\Repository\RestaurantSettingsRepository;
use App\Service\BookingManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Requirement\Requirement;

#[IsGranted('ROLE_USER')]
#[Route('/reservation')]
final class ReservationController extends AbstractController
{


    public  function __construct(
        private readonly EntityManagerInterface $em,
    ) {}


    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(BookingRepository $bookingRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $bookings = $bookingRepository->findBy(['client' => $user], ['datetime' => 'DESC']);
        return $this->render('reservation/index.html.twig', [
            'bookings' => $bookings,
        ]);
    }







    #[Route('/add', name: 'app_reservation', methods: ['GET', 'POST'])]
    public function new(Request $request, RestaurantSettingsRepository $restaurantSettingsRepository, BookingManager $bookingManager): Response

    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $Booking = new Booking();
        $Booking->setAllergies($user->getAllergies());
        $Booking->setGuestCount($user->getGuestCount());
        $Booking->setClient($user);

        // Default reservation date to tomorrow at 7 PM
        $Booking->setDatetime((new \DateTimeImmutable())->modify('+1 day')->setTime(19, 0));

        $BookingForm = $this->createForm(BookingType::class, $Booking);

        $BookingForm->handleRequest($request);
        if ($BookingForm->isSubmitted() && $BookingForm->isValid()) {

            # Get restaurant settings
            $settings = $restaurantSettingsRepository->findOneBy([]) ?? new RestaurantSettings();

            $errors = $bookingManager->getBookingError($settings, $Booking);
            if ($errors) {
                $this->addFlash('error', $errors);
                return $this->redirectToRoute('app_reservation');
            }

            $this->em->persist($Booking);
            $this->em->flush();
            $this->addFlash('success', 'Votre réservation a bien été prise en compte !');

            return $this->redirectToRoute('app_reservation_index');
        }

        return $this->render('reservation/new.html.twig', [
            'bookingForm' => $BookingForm,
        ]);
    }








    #[Route('/delete/{id}', name: 'app_reservation_delete', methods: ['DELETE'], requirements: ['id' => Requirement::POSITIVE_INT])]
    public function delete(Booking $booking, Request $request): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($booking->getClient() !== $user) {
            $this->addFlash('error', 'Vous ne pouvez pas annuler cette réservation.');
            return $this->redirectToRoute('app_reservation_index');
        }
        if ($booking->getDatetime() <= new \DateTimeImmutable()->modify('+4 hours')) {
            $this->addFlash('error', 'Vous ne pouvez pas annuler une réservation passée ou  moins de 4 heures avant l\'heure prévue.');
            return $this->redirectToRoute('app_reservation_index');
        }


        if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->request->get('_token'))) {
            $this->em->remove($booking);
            $this->em->flush();
            $this->addFlash('success', 'La réservation a été annulée avec succès.');
        }

        return $this->redirectToRoute('app_reservation_index');
    }
}
