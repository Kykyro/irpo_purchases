<?php


namespace App\Command;


use App\Entity\ContractingTables;
use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\PurchasesDump;
use App\Entity\UserInfo;
use App\Services\ContractingXlsxService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Shapecode\Bundle\CronBundle\Annotation\CronJob;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * @CronJob("15 3 * * 5,6")
 */
class saveContractingCommand extends Command
{

    protected static $defaultName = 'app:save:contracting';

    private $entity_manager;

    public function __construct(EntityManagerInterface $em, ContractingXlsxService $contractingXlsxService)
    {
        $this->entity_manager = $em;
        $this->contractingService = $contractingXlsxService;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Сохранение контрактации')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $types = [
            'Контрактация (Производственные кластеры)' => 'cluster',
            'Контрактация (Малые кластеры Лот 1)' => 'lot_1',
            'Контрактация (Малые кластеры Лот 2)' => 'lot_2',
        ];
        $io = new SymfonyStyle($input, $output);
        $now = new \DateTimeImmutable('now');
        $year = $now->format('Y');

        foreach ($types as $type)
        {
            $contractingTable = new ContractingTables();
            $contractingTable->setCreatedAt(new \DateTimeImmutable('now'));
            $contractingTable->setName($this->contractingService->downloadTable((int)$year, null, $type, true));
            $contractingTable->setType($type);
            $contractingTable->setYear($year);
            $this->entity_manager->persist($contractingTable);
        }


        $this->entity_manager->flush();

        $io->success(sprintf('Таблица контрактации сохранена'));

        return 0;
    }
}