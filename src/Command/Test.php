<?php

namespace LaminasScaffold\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class Test extends Command {

    public function __construct(mixed $name = null) {
        parent::__construct($name);
        $this->configure();
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        var_dump($input->getOptions());
        return 1;
    }

    protected function configure() {
        $this->addOption('option', 'opop', InputOption::VALUE_OPTIONAL);
    }

}
