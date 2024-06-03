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


class addCategoryUAVsTypeEquipmentBasCommand extends Command
{

    protected static $defaultName = 'app:bas:addcategory';

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
            ->setDescription('Добавить категории на БАС')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $equipments = $this->entity_manager->getRepository(UAVsTypeEquipment::class)
            ->createQueryBuilder('a')
            ->getQuery()
            ->getResult();


        foreach ($equipments as $equipment){

            switch ($equipment->getName()) {
                case 'Агропромышленная БАС':
                case 'Беспилотная авиационная система мультироторного типа с вариативными целевыми нагрузками':
                case 'Видеокоптер для мониторинга и тепловизионной съемки в режиме реального времени':
                    $equipment->setCategory('Мультиротор легкий');
                    break;
                case 'Беспилотная авиационная система самолетного типа с вариативными целевыми нагрузками':
                case 'Беспилотная авиационная система самолетного типа с ДВС':
                    $equipment->setCategory('Самолет легкий');
                    break;
                case 'Конструктор спортивного квадрокоптера':
                case 'Программируемый учебный квадрокоптер':
                case 'Программируемый учебный набор квадрокоптера':
                    $equipment->setCategory('Образовательные БАС');
                    break;
                default:
                    $equipment->setCategory(null);
            }



            $this->entity_manager->persist($equipment);
        }

        $this->entity_manager->flush();


        $io->success(sprintf('категории добавлены'));

        return 0;
    }
}