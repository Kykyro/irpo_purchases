<?php


namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class testCommand extends Command
{

    protected static $defaultName = 'app:purchases:dump';

    public function __construct()
    {


        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Сохранить состояние закупок для всех кластеров')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->success(sprintf('Состояние закупок сохраненно'));

        return 0;
    }
}