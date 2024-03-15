<?php


namespace App\Command;


use App\Entity\ProcurementProcedures;
use App\Entity\ReadinessMapArchive;
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
class addEventBasRoadMapCommand extends Command
{

    protected static $defaultName = 'app:bas:createevent';

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
        $events = [
          [
              'Утвержден региональный координатор, ответственный за реализацию мероприятий по созданию и функционированию специализированных классов (кружков) и центров практической подготовки',
              new \DateTimeImmutable("2023-12-29")
          ],
          [
              'Утверждено должностное лицо, ответственное за реализацию мероприятий по созданию и функционированию специализированных классов (кружков) и центров практической подготовки',
              new \DateTimeImmutable("2023-12-29")
          ],
          [
              'Сформирован и согласован перечень оборудования для специализированных классов (кружков) и центров практической подготовки',
              new \DateTimeImmutable("2024-02-15")
          ],
          [
              'Согласованы и утверждены дизайн-проекты специализированных классов (кружков) и центров практической подготовки',
              new \DateTimeImmutable("2024-02-15")
          ],
          [
              'Сформирован план закупок оборудования, работ, услуг для оснащения специализированных классов (кружков) и центров практической подготовки',
              new \DateTimeImmutable("2024-02-15")
          ],
          [
              'Объявлены закупки товаров, работ, услуг для создания специализированных классов (кружков) и центров практической подготовки',
              new \DateTimeImmutable("2024-03-01")
          ],
          [
              'Обучение педагогических работников в рамках мероприятий по обучению и подготовке квалифицированных педагогических кадров для обеспечения функционирования специализированных классов (кружков) и центров практической подготовки',
              null
          ],
          [
              'Выполнены ремонтные работы',
              new \DateTimeImmutable("2024-05-01")
          ],
          [
              'Завершено оснащение специализированных классов (кружков) или центров практической подготовки (доставлено, установлено и введено в эксплуатацию оборудование)',
              new \DateTimeImmutable("2024-06-01")
          ],
          [
              'Открытие специализированных классов (кружков) и центров практической подготовки ',
              new \DateTimeImmutable("2024-07-01")
          ],
          [
              'Проведен мониторинг оснащения специализированных классов (кружков) и центров практической подготовки ',
              new \DateTimeImmutable("2024-07-01")
          ],
        ];

        foreach ($clusters as $cluster){
            foreach ($events as $_event)
            {
                $event = new UsersEvents();
                $event->setType('road_map');
                $event->setName($_event[0]);
                $event->setFinishDate($_event[1]);
                $this->entity_manager->persist($event);
                $cluster->addUsersEvent($event);

            }
            $this->entity_manager->persist($cluster);
        }

        $this->entity_manager->flush();


        $io->success(sprintf('События для БАС по 2024 году созданы'));

        return 0;
    }
}