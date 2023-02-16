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


///**
// * @CronJob("1 *\/1 * * 5")
// */
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
        $io = new SymfonyStyle($input, $output);
        $now = new \DateTimeImmutable('now');
        $year = $now->format('Y');
        $contractingTable = new ContractingTables();
        $contractingTable->setCreatedAt(new \DateTimeImmutable('now'));
        $contractingTable->setName($this->contractingService->generateTable((int)$year));

        $this->entity_manager->persist($contractingTable);
        $this->entity_manager->flush();

        $io->success(sprintf('Таблица контрактации сохранена'));

        return 0;
    }
}