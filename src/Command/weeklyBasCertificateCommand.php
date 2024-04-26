<?php


namespace App\Command;


use App\Entity\ContractCertification;
use App\Entity\ProcurementProcedures;
use App\Entity\RepairDump;
use App\Entity\RepairDumpGroup;
use App\Entity\UAVsCertificate;
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


///**
// * @CronJob("5 3 * * 2")
// */
class weeklyBasCertificateCommand extends Command
{

    protected static $defaultName = 'app:certificatebas:bas';

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
            ->setDescription('Обновить статус справки о оснащении БАС')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $clusters = $this->entity_manager->getRepository(User::class)
            ->createQueryBuilder('a')
            ->leftJoin('a.user_info', 'uf')
            ->andWhere('a.roles LIKE :role_1')
            ->setParameter('role_1', "%ROLE_BAS%")
            ->getQuery()
            ->getResult();

        foreach ($clusters as $cluster){

            $certificate = $cluster->getUAVsCertificate();
            if(is_null($certificate))
            {
                $cluster->setUAVsCertificate(new UAVsCertificate());
                $certificate = $cluster->getUAVsCertificate();
            }

            $certificate->setStatus('Справка не направлена');
            $this->entity_manager->persist($cluster);
            $this->entity_manager->persist($certificate);
        }




        $this->entity_manager->flush();


        $io->success(sprintf('Созданы новые записи по справки по сонащению бас'));

        return 0;
    }
}