<?php

namespace LaminasScaffold\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class Createmodule extends Command {

    public function __construct(mixed $name = null) {
        parent::__construct($name);
        $this->configure();
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        $moduleName = $input->getOption('module-name');
        if (!$this->validate($moduleName)) {
            throw new \Exception('Cannot Create');
        }
        echo 'Creating Module : ' . $moduleName . PHP_EOL;
        $startingDir = getcwd() . '/module/';
        $templateStruct = include __DIR__ . '/../../config/modulestruct.config.php';
        var_dump($directoryStruct);
        return 1;
    }
    
    protected function createDirectorySturcture($startingDir, $directoryStructure){
        
    }

    protected function configure() {
        $this->addOption('module-name', 'mname', InputOption::VALUE_REQUIRED);
    }

    protected function validate($moduleName) {
        $validator = new \Laminas\I18n\Validator\Alpha(false);
        $valid = $validator->isValid($moduleName);
        if ($valid === false) {
            $message = '';
            $errors = $validator->getMessages();
            array_walk_recursive($errors, function($value) use(&$message) {
                $message .= !is_array($value) ? ($value . PHP_EOL) : '';
            });
            throw new \Exception($message);
        }
        return true;
    }

}
