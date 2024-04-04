<?php


namespace App\Command;


use App\Entity\ContractingTables;
use App\Entity\ProcurementProcedures;
use App\Entity\ReadinessMapSaves;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\PurchasesDump;
use App\Entity\UserInfo;
use App\Services\ContractingXlsxService;
use App\Services\ReadinessMapXlsxService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Shapecode\Bundle\CronBundle\Annotation\CronJob;
use Symfony\Component\Validator\Constraints\DateTime;


///**
// * @CronJob("30 3 * * 5,6")
// */
class saveReadinessMapNewCommand extends Command
{

    protected static $defaultName = 'app:save:rmnew';

    private $entity_manager;

    public function __construct(EntityManagerInterface $em, ReadinessMapXlsxService $readinessMapXlsxService)
    {
        $this->entity_manager = $em;
        $this->readinessMapXlsxService = $readinessMapXlsxService;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Сохранение карт готовности')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
            ->addArgument('year', InputArgument::REQUIRED, 'Год?')
            ->addArgument('type', InputArgument::REQUIRED, 'Тип')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $types = [
            'Карты готовности (Производственные кластеры)' => 'cluster',
            'Карты готовности (Малые кластеры Лот 1)' => 'lot_1',
            'Карты готовности (Малые кластеры Лот 2)' => 'lot_2',
        ];
        $io = new SymfonyStyle($input, $output);

        $year = $input->getArgument('year');
        $type = $input->getArgument('type');

//        foreach ($types as $type)
//        {
            $contractingTable = new ReadinessMapSaves();
            $contractingTable->setCreatedAt(new \DateTimeImmutable('now'));
            $contractingTable->setName($this->readinessMapXlsxService->downloadTable((int)$year, $type, true));
            $contractingTable->setType($type);
            $contractingTable->setYear($year);
            $this->entity_manager->persist($contractingTable);
//        }


        $this->entity_manager->flush();

        $io->success(sprintf('Карты готовности сохранены'));

        return 0;
    }
}