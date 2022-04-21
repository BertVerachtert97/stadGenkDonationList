<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Stopwatch\Stopwatch;

use function Symfony\Component\String\u;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    private $io;

    private $entityManager;
    private $passwordEncoder;
    private $validator;
    private $users;

    public function __construct (
        EntityManagerInterface $em,
        UserPasswordHasherInterface $encoder,
        Validator $validator,
        UserRepository $users
    ) {
        parent::__construct();

        $this->entityManager = $em;
        $this->passwordEncoder = $encoder;
        $this->validator = $validator;
        $this->users = $users;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates users and stores them in the database')
            ->setHelp($this->getCommandHelp())
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the new user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The password of the new user')
        ;
    }

    public function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('password') && null !== $input->getArgument('email')) {
            return;
        }

        $this->io->title('Add User Command Interactive Wizard');
        $this->io->text([
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:create-user email@example.com password',
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
        ]);

        $email = $input->getArgument('email');
        if (null !== $email) {
            $this->io->text(' > <info>Email</info>: ' . $email);
        } else {
            $email = $this->io->ask('Email', null, [$this->validator, 'validateEmail']);
            $input->setArgument('email', $email);
        }

        $password = $input->getArgument('password');
        if (null !== $password) {
            $this->io->text(' > <info>Password</info>: ' . u('*')->repeat(u($password)->length()));
        } else {
            $password = $this->io
                ->askHidden('Password (your type will be hidden)', [$this->validator, 'validatePassword']);
            $input->setArgument('password', $password);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('create-user-command');

        $plainPassword = $input->getArgument('password');
        $email = $input->getArgument('email');

        $this->validateUserData($email, $plainPassword);

        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);

        $encodedPassword = $this->passwordEncoder->hashPassword($user, $plainPassword);
        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success(sprintf('%s was successfully created', $user->getEmail()));

        $event = $stopwatch->stop('create-user-command');

        return 0;
    }

    private function validateUserData($email, $plainPassword): void
    {
        $existingUser = $this->users->findOneBy(['email' => $email]);

        if (null !== $existingUser) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email', $email));
        }

        $this->validator->validatePassword($plainPassword);
        $this->validator->validateEmail($email);
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
        The <info>%command.name%</info> command creates new users and saves them in the database:
        By default the command creates regular users.
          <info>php %command.full_name%</info> email password
        If you forget any of the three required arguments, the command will ask you to
        provide the missing values:
          # command will ask you for the password
          <info>php %command.full_name%</info> <comment>email</comment>
         
          # command will ask you for all arguments
          <info>php %command.full_name%</info>
        HELP;
    }
}
