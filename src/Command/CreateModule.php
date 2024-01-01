<?php

namespace LaminasScaffold\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use LaminasScaffold\Abstraction\ModuleCreatorInterface;

class CreateModule extends Command {

    private ModuleCreatorInterface $moduleCreator;

    public function __construct(ModuleCreatorInterface $moduleCreator, mixed $name = null) {
        $this->moduleCreator = $moduleCreator;
        parent::__construct($name);
        $this->configure();
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        $ui = new SymfonyStyle($input, $output);
        $moduleName = $ui->ask('Enter Module Name ');
        $this->moduleCreator->setModuleName($moduleName);

        try {
            $ui->text('Creating module ... ');
            $this->moduleCreator->create();
            shell_exec('composer dump-autoload');
            $ui->success('Module "' . $moduleName . '" created successfully !');
            return Command::SUCCESS;
        } catch (\Exception $ex) {
            $ui->error($ex->getMessage());
            return Command::FAILURE;
        }
    }

}
