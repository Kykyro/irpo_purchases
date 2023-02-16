<?php


namespace App\Command;


use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\PurchasesDump;
use App\Entity\UserInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Shapecode\Bundle\CronBundle\Annotation\CronJob;


///**
// * @CronJob("1 *\/1 * * 2")
// */
class openAccessCommand extends Command
{

    protected static $defaultName = 'app:access:open';

    private $entity_manager;

    public function __construct(EntityManagerInterface $em)
    {

        $this->entity_manager = $em;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Открыть доступ для заполнения закупок')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $currentTime = new \DateTime('now');
        $hour = intval($currentTime->format('G'));
        $open_count = 0;
        $schedule = [
            12 => 12,
            13 => 11,
            14 => 10,
            15 => 9,
            16 => 8,
            17 => 7,
            18 => 6,
            19 => 5,
            20 => 4,
            21 => 3,
            22 => 2,
            23 => 1,
        ];
        if(array_key_exists($hour, $schedule))
        {
            $timezone = $schedule[$hour];
            $regions = $this->entity_manager->getRepository(RfSubject::class)->getRegionByTimeZone($timezone);
            foreach ($regions as $region)
            {
                $users_info = $this->entity_manager->getRepository(UserInfo::class)->getByRegion($region);
                foreach ($users_info as $user_info)
                {
                    $user_info->setAccessToPurchases(true);
                    $this->entity_manager->persist($user_info);
                    $open_count++;
                }


            }
            $this->entity_manager->flush();
        }


        $io->success(sprintf('Доступ открыт для '.$open_count.' кластеров'));

        return 0;
    }
}