<?php

namespace App\Command;

use App\Entity\Visit;
use App\Repository\VisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class RemindeVisitCommand extends Command
{
    protected static $defaultName = 'app:reminde-visit';

    private $em;

    private $mailer;

    /**
     * RemindeVisitCommand constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        parent::__construct();
        $this->em = $entityManager;
        $this->mailer = $mailer;
    }


    protected function configure()
    {
        $this->setDescription('Remind tomorrow visits')        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var VisitRepository $visitsRepo */
        $visitsRepo = $this->em->getRepository(Visit::class);

        $visits = $visitsRepo->findVisitTomorrow();

        if (empty($visits)) {
            return Command::SUCCESS;
        }

        foreach ($visits as $visit) {

            $email = new TemplatedEmail();
            $email->from(new Address('testprojectuser999@gmail.com', 'Gabinet Stomatologiczny'));
            $email->to($visit['email']);
            $email->subject('Jutro czeka Cię wizyta u stomatologa');
            $email->context([
                'visit' => $visit['visitDate'],
                'fullname' => $visit['name'] . ' ' . $visit['lastname']
            ]);
            $email->htmlTemplate('visits/remindVisitEmail.html.twig');

            $this->mailer->send($email);
        }

        return Command::SUCCESS;
    }
}
