<?php

namespace LaminasScaffold\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use LaminasScaffold\Abstraction\ActionCreatorInterface;

class CreateAction extends Command {

    private string $moduleName;

    private string $controllerName;

    private string $actionName;

    private ActionCreatorInterface $actionCreatorService;

    public function __construct(ActionCreatorInterface $actionCreatorService, mixed $name = null) {
        parent::__construct($name);
        $this->actionCreatorService = $actionCreatorService;
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        $ui = new SymfonyStyle($input, $output);
        $this->moduleName = $ui->ask('Enter Module Name ');
        $this->controllerName = $ui->ask('Enter Controller Name ');
        $this->actionName = $ui->ask('Enter Action Name ');
        
        try{
            $this->actionCreatorService->setModuleName($this->moduleName);
            $this->actionCreatorService->setControllerName($this->controllerName);
            $this->actionCreatorService->setActionName($this->actionName);
            $this->actionCreatorService->create();
            $ui->success("Route added succesfully");
            return Command::SUCCESS;
        } catch (\Exception $ex) {
            $ui->error($ex->getMessage());
        }
        $ui->error("Failed to create route");
        return Command::FAILURE;
        
    }

}
