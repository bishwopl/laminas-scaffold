<?php

namespace LaminasScaffold\Service;

use Laminas\Config\Writer\PhpArray as ArrayConfigWriter;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\MethodGenerator;
use LaminasScaffold\Service\PSR4Tools;
use LaminasScaffold\Abstraction\ControllerCreatorInterface;

class ControllerCreatorService implements ControllerCreatorInterface {

    /**
     * @var string
     */
    private $moduleName;

    /**
     * @var string
     */
    private $controllerName;

    /**
     * @var bool
     */
    private $useFactory = true;

    /**
     * @var string
     */
    private $modulePath;

    /**
     * @var string
     */
    private $configPath;
    
    /**
     * @var string
     */
    private $controllerFileName;

    /**
     * @param string $moduleName
     * @param string $controllerName
     * @param bool $useFactory
     */
    public function __construct(string $moduleName = NULL, string $controllerName = NULL, bool $useFactory = true) {
        $this->moduleName = $moduleName;
        $this->controllerName = $controllerName . 'Controller';
        $this->useFactory = (bool) $useFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        $this->validate();
        $this->modulePath = PSR4Tools::findPathFromNameSpace($this->moduleName)[0];
        $this->configPath = $this->modulePath . '/../config/module.config.php';
        $this->controllerFileName = $this->modulePath . '/Controller/' . $this->controllerName . '.php';
        $this->createController();
        $this->createConfig();
        if ($this->useFactory) {
            $this->createControllerFactory();
        }
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function setControllerName(string $controllerName) {
        $this->controllerName = $controllerName . 'Controller';
    }

    /**
     * {@inheritdoc}
     */
    public function setModuleName(string $moduleName) {
        $this->moduleName = $moduleName;
    }

    /**
     * {@inheritdoc}
     */
    public function setUseFactory(bool $useFactory) {
        $this->useFactory = (bool) $useFactory;
    }
    
    private function validate(){
        if(!PSR4Tools::doesModuleExists($this->moduleName)){
            throw new \Exception('Module with name '.'"'.$this->moduleName.'" doesnot exists !');
        }
    }

    /**
     * Adds controller configuration to module.config.php file
     */
    private function createConfig() {
        $configWriter = new ArrayConfigWriter();
        $configWriter->setUseBracketArraySyntax(true);
        $configWriter->setUseClassNameScalars(true);
        $currentConfig = include $this->configPath;
        $currentConfig['controller'][$this->moduleName . '\\Controller\\' . $this->controllerName] = $this->useFactory ? $this->moduleName . '\\Controller\\Factory\\' . $this->controllerName . 'Factory' : \Laminas\ServiceManager\Factory\InvokableFactory::class;
        $configWriter->toFile($this->configPath, $currentConfig);
    }

    /**
     * Creates controller
     */
    private function createController() {
        $file = new FileGenerator([
            'classes' => [
                new ClassGenerator(
                        $this->controllerName,
                        $this->moduleName . '\\Controller',
                        null,
                        'Laminas\Mvc\Controller\AbstractActionController',
                        [],
                        [],
                        [],
                        null
                ),
            ],
        ]);
        file_put_contents($this->controllerFileName, $file->generate());

    }

    /**
     * Creates controller factory
     */
    private function createControllerFactory() {
        $factoryFile = new FileGenerator([
            'classes' => [
                new ClassGenerator(
                        $this->controllerName . 'Factory',
                        $this->moduleName . '\\Controller\\Factory',
                        null,
                        null,
                        ['Laminas\ServiceManager\Factory\FactoryInterface'],
                        [],
                        [
                    new MethodGenerator(
                            '__invoke',
                            [
                        ['name' => 'container', 'type' => '\\Psr\\Container\\ContainerInterface'],
                        'requestedName',
                        ['name' => 'options', 'type' => 'array', 'defaultvalue' => null],
                            ],
                            MethodGenerator::FLAG_PUBLIC,
                            "return new \\" . $this->moduleName . "\\Controller\\" . $this->controllerName . "();"
                    ),
                        ],
                        null
                ),
            ],
        ]);
        file_put_contents($this->modulePath . '/Controller/Factory/' . $this->controllerName . 'Factory.php', $factoryFile->generate());
    }

}
