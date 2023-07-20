<?php


namespace App\Command;


use App\Entity\ContractingTables;
use App\Entity\Employers;
use App\Entity\ProcurementProcedures;
use App\Entity\ReadinessMapSaves;
use App\Entity\RfSubject;
use App\Entity\User;
use App\Entity\PurchasesDump;
use App\Entity\UserInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Shapecode\Bundle\CronBundle\Annotation\CronJob;
use Symfony\Component\Validator\Constraints\DateTime;



class convertEmployersCommand extends Command
{

    protected static $defaultName = 'app:convert:employers';

    private $entity_manager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entity_manager = $em;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Конвертировать работодателей')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);

        $users_info = $this->entity_manager->getRepository(UserInfo::class)->findAll();


        $emploers_array = [];
        foreach ($users_info as $user_info)
        {
            if($user_info->getListOfEmployersOld())
                $emploers_array = array_merge($emploers_array, $user_info->getListOfEmployersOld());
        }
        $emploers_array = array_unique($emploers_array);

        foreach ($emploers_array as $employers)
        {
            $_userInfo = $this->entity_manager->
                getRepository(UserInfo::class)
                ->createQueryBuilder('uf')
                ->andWhere('uf.listOfEmployers LIKE :empl')
                ->setParameter('empl', "%$employers%")
                ->getQuery()
                ->getResult();

            $empl_entity = new Employers();
            $empl_entity->setName($employers);
            foreach ($_userInfo as $uf)
            {
                $empl_entity->addUserInfo($uf);
            }
            $io->success(sprintf("$employers"));
            $this->entity_manager->persist($empl_entity);
        }


        $this->entity_manager->flush();
//        dd();
        $io->success(sprintf('Работадатели конвертированы'));

        return 0;
    }
}