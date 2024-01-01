<?php

use Laminas\ServiceManager\ServiceLocatorInterface;
use LaminasScaffold\Command\Test;
use LaminasScaffold\Command\CreateModule;
use LaminasScaffold\Command\CreateController;
use LaminasScaffold\Command\CreateAction;
use LaminasScaffold\Service\ModuleCreatorService;
use LaminasScaffold\Service\ControllerCreatorService;
use LaminasScaffold\Service\ActionCreatorService;

return [
    'laminas-cli' => [
        'commands' => [
            'make:test' => Test::class,
            'make:module' => CreateModule::class,
            'make:controller' => CreateController::class,
            'make:action' => CreateAction::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            CreateModule::class => function(ServiceLocatorInterface $container) {
                $config = $container->get('Config');
                return new CreateModule(
                        new ModuleCreatorService(
                                getcwd() . '/module/',
                                $config['laminas-scaffold']['templates']['simpleModule']
                        )
                );
            },
            CreateController::class => function() {
                return new CreateController(new ControllerCreatorService());
            },
            CreateAction::class => function() {
                return new CreateAction(new ActionCreatorService());
            },
        ],
    ],
    'laminas-scaffold' => [
        'templates' => [
            'simpleModule' => [
                '__ModuleName__' => [
                    'config',
                    'src' => [
                        'Controller' => [
                            'Factory',
                        ],
                        'Service' => [
                            'Factory',
                        ],
                    ],
                    'view' => [
                        '__module-name__'
                    ],
                ],
            ],
        ],
    ],
];
