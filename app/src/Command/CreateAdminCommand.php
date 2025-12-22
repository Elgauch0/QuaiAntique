<?php

namespace App\Command;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'create a new admin user',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Créer un nouvel utilisateur admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $userAdmin = $this->em->getRepository('App\Entity\User')->getAdminUser();

        if ($userAdmin) {
            $io->error('Un utilisateur admin existe déjà !');

            $answer = $io->ask(
                'Écrasez-le et créez-en un nouveau ? (oui/non)',
                'non',
                function ($answer) {
                    $answer = strtolower($answer);
                    if (!in_array($answer, ['oui', 'non'])) {
                        throw new \RuntimeException('Réponse invalide.');
                    }
                    return $answer;
                }
            );

            if ($answer !== 'oui') {
                $io->warning('Commande annulée.');
                return Command::SUCCESS;
            }
        } else {

            $userAdmin = new \App\Entity\User();
        }

        $email = $io->ask('Adresse email de l\'admin', null, function ($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('Adresse email invalide');
            }
            return $email;
        });

        $password = $io->askHidden('Mot de passe de l\'admin');

        $userAdmin->setEmail($email);
        $hashedPassword = $this->passwordHasher->hashPassword($userAdmin, $password);
        $userAdmin->setPassword($hashedPassword);
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setName('ADMINQUAIANTIQUE');
        $userAdmin->setGuestCount(0);
        $userAdmin->setAllergies(null);
        $this->em->persist($userAdmin);
        $this->em->flush();

        $io->success('Utilisateur admin créé avec succès !');

        return Command::SUCCESS;
    }
}
