<?php

namespace App\Command;

use App\Entity\Calendrier;
use App\Manager\CalendarManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


/**
 * Class SynchroCalendrierCommand
 * @package App\Command
 *
 * Pour implementer cette fonction dans un crontab :
 * 0 0 * * * /var/www/Zap-Pas && php bin/console --env=prod app:synchro:cal
 *
 * php bin/console app:synchro:cal
 */
class SynchroCalendrierCommand extends Command
{
    protected static $defaultName = 'app:synchro:cal';
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->em = $entityManager;

    }

    protected function configure()
    {
        $this
            ->setDescription('this command set synchronisation for all calendar')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $repo = $this->em->getRepository(Calendrier::class);
        $calendriers = $repo->findAllCalAvailable();
        $managerCal = new CalendarManager($this->em);
        foreach ($calendriers as $cal) {
            $managerCal->synchroCalendar($cal);
        }

        $io = new SymfonyStyle($input, $output);

        $io->success('Calendriers synchronisés !');

        return 0;
    }
}
