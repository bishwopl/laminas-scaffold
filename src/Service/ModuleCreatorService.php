<?php

namespace LaminasScaffold\Service;

use Laminas\Config\Writer\PhpArray as ArrayConfigWriter;
use Laminas\Config\Reader\Json as JsonReader;
use Laminas\Config\Writer\Json as JsonWriter;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\MethodGenerator;
use LaminasScaffold\Service\ReplaceNameService;
use LaminasScaffold\Service\DirectoryTree;
use LaminasScaffold\Abstraction\ModuleCreatorInterface;

class ModuleCreatorService implements ModuleCreatorInterface {

    /**
     * Name of the module to be created
     * @var string 
     */
    private $moduleName;

    /**
     * Directory structure of module
     * @var array 
     */
    private $directoryStructure;

    /**
     * Full path for the module
     * @var string 
     */
    private $startingDir;

    public function __construct(string $startingDir, array $directoryStructure) {
        $this->startingDir = $startingDir;
        $this->directoryStructure = $directoryStructure;
    }

    /**
     * Checks if module exists
     * @return bool
     */
    public function doesModuleExists() : bool {
        return is_dir($this->startingDir . '/' . $this->moduleName);
    }

    /**
     * @inheritDoc
     */
    public function setModuleName(string $moduleName) : void {
        $this->moduleName = $moduleName;
    }
    
    /**
     * Creates module
     * @return void
     * @throws \Exception
     */
    public function create() : void {
        $this->validate();
        if($this->doesModuleExists()){
            throw new \Exception('Module with name "' . $this->moduleName . '" already exists!');
        }
        $this->createDirectorySturcture();
        $this->createConfigFile();
        $this->createModuleClassFile();
        $this->addAutoload();
        return;
    }
    
    /**
     * Creates directories for the module
     * @return void
     */
    private function createDirectorySturcture() : void {
        $converter = new \Laminas\Filter\Word\CamelCaseToDash();
        $dashedName = strtolower($converter->filter(lcfirst($this->moduleName)));
        $this->directoryStructure = ReplaceNameService::replaceFromArray($this->directoryStructure, '__ModuleName__', $this->moduleName);
        $this->directoryStructure = ReplaceNameService::replaceFromArray($this->directoryStructure, '__module-name__', $dashedName);
        DirectoryTree::createDirectorySturcture($this->directoryStructure, $this->startingDir);
        return;
    }

    /**
     * Creates empty configuration file for the module
     * @return void
     */
    private function createConfigFile() : void {
        $filePath = $this->startingDir . '/' . $this->moduleName . '/config/module.config.php';
        $writer = new ArrayConfigWriter();
        $writer->setUseBracketArraySyntax(true);
        $writer->toFile($filePath, [
            'view_manager' => [
                'template_path_stack' => [
                    __DIR__ . '/../view',
                ],
            ],
        ]);
        return;
    }

    /**
     * Create default Module.php file for the module
     * @return void
     */
    private function createModuleClassFile() : void {
        $file = new FileGenerator([
            'classes' => [
                new ClassGenerator(
                    'Module',
                    $this->moduleName,
                    null,
                    null,
                    ['Laminas\ModuleManager\Feature\ConfigProviderInterface'],
                    [],
                    [
                        new MethodGenerator(
                            'getConfig',
                            [],
                            MethodGenerator::FLAG_PUBLIC,
                            "return include __DIR__ . '/../config/module.config.php';"
                        ),
                    ],
                    null
                ),
            ],
            'uses' => [
                'Laminas\ModuleManager\Feature\ConfigProviderInterface'
            ]
        ]);
        file_put_contents($this->startingDir . '/' . $this->moduleName . '/src/Module.php', $file->generate());
        return;
    }

    /**
     * Validates Module name
     * @return boolean
     * @throws \Exception
     */
    private function validate() : bool {
        $filter = new \Laminas\I18n\Filter\Alpha();
        $this->moduleName = $filter->filter($this->moduleName);

        $validator = new \Laminas\I18n\Validator\Alpha();
        $valid = $validator->isValid($this->moduleName);
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
    
    /**
     * Puts PSR-4 autoload entry in composer.json file
     * @return void
     */
    private function addAutoload() : void {
        $configFileName = getcwd() . '/composer.json';
        $reader = new JsonReader();
        $data = $reader->fromFile($configFileName);
        $data['autoload']['psr-4'][$this->moduleName . '\\'] = $this->startingDir . '/' . $this->moduleName . '/src';
        $writer = new JsonWriter();
        file_put_contents($configFileName, $writer->toString($data));
        return;
    }

}
