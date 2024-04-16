<?php


namespace App\Command;


use App\Entity\ProcurementProcedures;
use App\Entity\ReadinessMapArchive;
use App\Entity\UAVsTypeEquipment;
use App\Entity\User;
use App\Entity\PurchasesDump;
use App\Entity\UsersEvents;
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


///**
// * @CronJob("25 3 * * 5,6")
// */
class addUAVsTypeEquipmentBasCommand extends Command
{

    protected static $defaultName = 'app:bas:createuavs';

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
            ->andWhere('uf.year = :year')
            ->setParameter('role', "%ROLE_BAS%")
            ->setParameter('year', "2024")
            ->getQuery()
            ->getResult();
        $types = [
          [
              'Агропромышленная БАС',
          ],
          [
              'Беспилотная авиационная система мультироторного типа с вариативными целевыми нагрузками',
          ],
          [
              'Беспилотная авиационная система самолетного типа с вариативными целевыми нагрузками',
          ],
          [
              'Беспилотная авиационная система самолетного типа с ДВС',
          ],
          [
              'Видеокоптер для мониторинга и тепловизионной съемки в режиме реального времени',
          ],
          [
              'Конструктор спортивного квадрокоптера',
          ],
          [
              'Программируемый учебный квадрокоптер',
          ],
          [
              'Программируемый учебный набор квадрокоптера',
          ],
        ];

        foreach ($clusters as $cluster){
            foreach ($types as $type)
            {
                $eqpment = new UAVsTypeEquipment();
                $eqpment->setName($type[0]);
                $eqpment->setUser($cluster);
                $this->entity_manager->persist($eqpment);
            }
            $this->entity_manager->persist($cluster);
        }

        $this->entity_manager->flush();


        $io->success(sprintf('типы БАС по 2024 году созданы'));

        return 0;
    }
}