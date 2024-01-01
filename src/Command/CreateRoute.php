<?php

namespace LaminasScaffold\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use LaminasScaffold\Abstraction\RouteCreatorInterface;

class CreateRoute extends Command {

    private string $moduleName;

    private string $controllerName;

    private string $actionName;

    private string $routeName;
    
    private RouteCreatorInterface $routerCreatorService;

    public function __construct(RouteCreatorInterface $routerCreatorService, mixed $name = null) {
        parent::__construct($name);
        $this->routerCreatorService = $routerCreatorService;
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        $ui = new SymfonyStyle($input, $output);
        $this->moduleName = $ui->ask('Enter Module Name ');
        $this->controllerName = $ui->ask('Enter Controller Name ');
        $this->actionName = $ui->ask('Enter Action Name ');
        $this->routeName = $ui->ask('Enter Route name ');
        
        try{
            $this->routerCreatorService->setModuleName($this->moduleName);
            $this->routerCreatorService->setControllerName($this->controllerName);
            $this->routerCreatorService->setActionName($this->actionName);
            $this->routerCreatorService->setRoute($this->routeName);
            $this->routerCreatorService->create();
            $ui->success("Route added succesfully");
            return Command::SUCCESS;
        } catch (\Exception $ex) {
            $ui->error($ex->getMessage());
        }
        $ui->error("Failed to create route");
        return Command::FAILURE;
        
    }

}
