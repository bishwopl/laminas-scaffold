<?php

namespace LaminasScaffold\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use LaminasScaffold\Abstraction\ControllerCreatorInterface;

class CreateController extends Command {

    private ControllerCreatorInterface $controllerCreator;

    public function __construct(ControllerCreatorInterface $controllerCreator, mixed $name = null) {
        parent::__construct($name);
        $this->controllerCreator = $controllerCreator;
        $this->configure();
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        $ui = new SymfonyStyle($input, $output);
        $moduleName = $ui->ask('Enter Module Name ');
        $controllerName = $ui->ask('Enter Controller Name ');
        $createFactory = $ui->choice("Create separate factory for controller", [1 => "Yes", 0 => "No"]);

        $this->controllerCreator->setModuleName($moduleName);
        $this->controllerCreator->setControllerName($controllerName);
        $this->controllerCreator->setUseFactory($createFactory === 1 || $createFactory == 'Yes');

        try {
            $this->controllerCreator->create();
            $ui->success('Controller created succefully');
            return Command::SUCCESS;
        } catch (\Exception $ex) {
            $ui->error($ex->getMessage());
            return Command::FAILURE;
        }
        return Command::FAILURE;
    }

}
