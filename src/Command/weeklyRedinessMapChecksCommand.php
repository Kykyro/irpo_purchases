<?php


namespace App\Command;


use App\Entity\ReadinessMapCheck;
use App\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Shapecode\Bundle\CronBundle\Annotation\CronJob;



class weeklyRedinessMapChecksCommand extends Command
{

    protected static $defaultName = 'app:readinessmap:checks';

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
            ->setDescription('Обновление проверок карт готовноти')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $clusters = $this->entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('a.roles LIKE :role_1 OR a.roles LIKE :role_2')
            ->andWhere('uf.readinessMapChecksRefresh = :access')
            ->setParameter('role_1', "%REGION%")
            ->setParameter('role_2', "%SMALL_CLUSTERS%")
            ->setParameter('access', true)
            ->getQuery()
            ->getResult();

        foreach ($clusters as $cluster){
            $ReadinessMapChecks = $cluster->getReadinessMapChecks()->last();
            if($ReadinessMapChecks)
            {
               $status_rm = $ReadinessMapChecks->getStatus()->last();
               if($status_rm)
               {
                   if($status_rm->getStatus() == 'На доработке')
                   {
                       continue;
                   }
               }

            }

            $readinessMapCheck = new ReadinessMapCheck($cluster);
            $this->entity_manager->persist($readinessMapCheck);
            $this->entity_manager->persist($cluster);

        }

        $this->entity_manager->flush();

        $io->success(sprintf('Проверка карт готовности обновлена'));

        return 0;
    }
}