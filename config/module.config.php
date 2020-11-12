<?php

use LaminasScaffold\Command\Test;
use LaminasScaffold\Command\Createmodule;

return [
    'laminas-cli' => [
        'commands' => [
            'laminas-sc:test' => Test::class,
            'laminas-sc:create-module' => Createmodule::class
        ],
    ],
];
