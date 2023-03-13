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
class noteToAllPurchasesWithoutPlanningDateCommand extends Command
{

    protected static $defaultName = 'app:note:planning';

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
            ->setDescription('Отправить сообщения закупкам без даты планируемой даты публикации')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $io->success(sprintf('Сообщения отправлены'));

        return 0;
    }
}