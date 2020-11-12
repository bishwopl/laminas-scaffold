<?php

return [
    'simple' => [
        'dir' => [
            '__ModuleName__' => [
                'config',
                'src'=>[
                    'Controller',
                    'Service',
                    'Factory',
                ],
                'view',
            ],
        ],
        'files' => [
            '__ModuleName__'=>[
                'config' => [
                    'modules.config.php'
                ],
                'src' => [
                    'Module.php'
                ]
            ],
        ],
    ],
];
