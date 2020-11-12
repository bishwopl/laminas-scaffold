<?php

/*
 * This config is for directory structure of Module
 */

$simple = [
    'dir' => [
        '__ModuleName__' => [
            'config',
            'src' => [
                'Controller' => [
                    'Factory'
                ],
                'Service' => [
                    'Factory'
                ],
                'Form' => [
                    'Factory'
                ],
                'Entity',
                'Command' => [
                    'Factory'
                ],
            ],
            'view',
        ],
    ],
    'files' => [
        '__ModuleName__' => [
            'config' => [
                'modules.config.php'
            ],
            'src' => [
                'Module.php'
            ]
        ],
    ],
];

return [
    'simple' => $simple
];

