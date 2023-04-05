<?php


namespace App\Command;


use App\Entity\ProcurementProcedures;
use App\Entity\ReadinessMapArchive;
use App\Entity\User;
use App\Entity\PurchasesDump;
use App\Services\certificateReadinessMap;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Shapecode\Bundle\CronBundle\Annotation\CronJob;


/**
 * @CronJob("5 3 * * 5,6")
 */
class weeklyReportReadinessMapCommand extends Command
{

    protected static $defaultName = 'app:readinessmap:dump';

    private $serializer;
    private $entity_manager;
    private $certificateService;
    private $params;

    public function __construct(ParameterBagInterface $params, SerializerInterface $serializer, EntityManagerInterface $em, certificateReadinessMap $readinessMap)
    {
        $this->serializer = $serializer;
        $this->entity_manager = $em;
        $this->certificateService = $readinessMap;
        $this->params = $params;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Сохранить справки по картам готовности для всех кластеров')
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
            ->andWhere('uf.year > :year')
            ->setParameter('role', "%REGION%")
            ->setParameter('year', "2022")
            ->getQuery()
            ->getResult();

        foreach ($clusters as $cluster){

            $file = $this->certificateService->getCertificate($cluster, true, $this->params->get('readiness_map_template_file'));
            if($file === false)
                continue;

            $archive= new ReadinessMapArchive();
            $archive->setUser($cluster);
            $archive->setCreatedAt(new \DateTimeImmutable('now'));

            $archive->setFile($file);
            $this->entity_manager->persist($archive);
        }



        $this->entity_manager->flush();


        $io->success(sprintf('Справки по картам готовности сохранены'));

        return 0;
    }
}