<?php


namespace App\Command;


use App\Entity\ContractCertification;
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
 * @CronJob("5 3 * * 2")
 */
class weeklyContractCertificateCommand extends Command
{

    protected static $defaultName = 'app:certificate:new';

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
            ->setDescription('Создать новые записи по справки о контрактации')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $clusters = $this->entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('a.roles LIKE :role_1 OR a.roles LIKE :role_2 OR a.roles LIKE :role_3')
            ->andWhere('uf.accessToPurchases = :access')
            ->setParameter('role_1', "%REGION%")
            ->setParameter('role_2', "%SMALL_CLUSTERS%")
            ->setParameter('role_3', "%ROLE_BAS%")
            ->setParameter('access', true)
            ->getQuery()
            ->getResult();

        foreach ($clusters as $cluster){
            $contractCertification = new ContractCertification($cluster->getUserInfo());
            $this->entity_manager->persist($contractCertification);
        }




        $this->entity_manager->flush();


        $io->success(sprintf('Созданы новые записи по справки о контрактации'));

        return 0;
    }
}