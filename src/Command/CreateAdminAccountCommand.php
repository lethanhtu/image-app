<?php

namespace App\Command;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\User;

class CreateAdminAccountCommand extends Command
{
    protected static $defaultName = 'CreateAdminAccount';

    protected $doctrine;
    protected $encoder;

    public function __construct(ManagerRegistry $doctrine, UserPasswordEncoderInterface $encoder)
    {
        $this->doctrine = $doctrine;
        $this->encoder = $encoder;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Create admin accounts')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of admin account')
            ->addArgument('password', InputArgument::REQUIRED, 'Password of admin account')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = $this->doctrine->getRepository(User::class)->findOneByUsername('admin');
        $hahsedPassword = $this->encoder->encodePassword(new User(), $password);
        $manager = $this->doctrine->getManager();

        if ($user && $input->getOption('force')) {
            $user->setEmail($email);
            $user->setPassword($hahsedPassword);
            $manager->persist($user);
            $manager->flush();
            $io->success('Admin account is updated successfully');
            return;
        }

        if (!$user) {
            $user = new User();
            $user->setUsername('admin');
            $user->setEmail($email);
            $user->setPassword($hahsedPassword);
            $user->setRole(User::ROLE_ADMIN);
            $user->setFullName('Administrator');
            $manager->persist($user);
            $manager->flush();
            $io->success('Admin account is created successfully');
            return;
        }

        $io->note('Admin account is already exist');
    }
}
