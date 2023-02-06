<?php


namespace App\Command;


use App\Entity\ProcurementProcedures;
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
 * @CronJob("5 3 * * 6")
 */
class weeklyReportCommand extends Command
{

    protected static $defaultName = 'app:purchases:dump';

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
            ->andWhere('a.roles LIKE :role')
            ->setParameter('role', "%REGION%")
            ->getQuery()
            ->getResult();

        foreach ($clusters as $cluster){

            $pp = $this->entity_manager->getRepository(ProcurementProcedures::class)
                ->createQueryBuilder('a')
                ->andWhere('a.user = :uid')
                ->setParameter('uid', $cluster->getId())
                ->getQuery()
                ->getResult();
            $jsonContent =  $this->serializer->serialize($pp, 'json',['groups' => ['dump_data']]);
            $jsonContent = utf8_encode($jsonContent);
            $purchasesDump = new PurchasesDump();
            $purchasesDump->setUser($cluster);
            $purchasesDump->getDump()->setDump($jsonContent);
            $this->entity_manager->persist($purchasesDump);
        }



        $this->entity_manager->flush();


        $io->success(sprintf('Состояние закупок сохраненно'));

        return 0;
    }
}