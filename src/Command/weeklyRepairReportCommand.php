<?php


namespace App\Command;


use App\Entity\ProcurementProcedures;
use App\Entity\RepairDump;
use App\Entity\RepairDumpGroup;
use App\Entity\User;
use App\Entity\PurchasesDump;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Shapecode\Bundle\CronBundle\Annotation\CronJob;


/**
 * @CronJob("5 3 * * 5,6")
 */
class weeklyRepairReportCommand extends Command
{

    protected static $defaultName = 'app:repair:dump';

    private $serializer;
    private $entity_manager;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->entity_manager = $em;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Сохранить состояние закупок для всех кластеров')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $clusters = $this->entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('a.roles LIKE :role')
            ->andWhere('uf.accessToPurchases = :access')
            ->setParameter('role', "%REGION%")
            ->setParameter('access', true)
            ->getQuery()
            ->getResult();

        foreach ($clusters as $cluster){
            $dumpGroup = new RepairDumpGroup();

            $adreses = $cluster->getClusterAddresses();
            foreach ($adreses as $adrese)
            {
                $zones = $adrese->getSortedClusterZones();
                foreach ($zones as $zone)
                {
                    $repair = $zone->getZoneRepair();
                    if(is_null($repair->getComment()))
                        $repair->setComment('-');
                    $jsonContent =  $this->serializer->serialize($repair, 'json',['groups' => ['dump_data']]);
                    $jsonContent = utf8_encode($jsonContent);
                    $dump = new RepairDump();
                    $dump->setRepair($repair);
                    $dump->setUser($cluster);
                    $dump->setDump($jsonContent);
                    $dump->setRepairDumpGroup($dumpGroup);
                    $this->entity_manager->persist($dump);
                }
            }
            $this->entity_manager->persist($dumpGroup);
        }




        $this->entity_manager->flush();


        $io->success(sprintf('Состояние закупок сохраненно'));

        return 0;
    }
}