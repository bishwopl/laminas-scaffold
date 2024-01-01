<?php

namespace LaminasScaffold\Service;

use Laminas\Code\Generator\MethodGenerator;
use LaminasScaffold\Service\PSR4Tools;
use LaminasScaffold\Abstraction\ActionCreatorInterface;

class ActionCreatorService implements ActionCreatorInterface {

    private string $moduleName;
    private string $controllerName;
    private string $actionName;
    private string $modulePath;
    private string $configPath;
    private string $controllerFileName;

    public function __construct() {
        
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        $this->validate();
        $this->modulePath = PSR4Tools::findPathFromNameSpace($this->moduleName)[0];
        $this->configPath = $this->modulePath . '/../config/module.config.php';
        $this->controllerFileName = $this->modulePath . '/Controller/' . $this->controllerName . '.php';

        $method = new MethodGenerator($this->actionName);
        $method->setReturnType(\Laminas\View\Model\ViewModel::class);
        $method->setVisibility(MethodGenerator::VISIBILITY_PUBLIC);
        $method->setBody('$vm = \Laminas\View\Model\ViewModel();'."\n".'return $vm;');

        $fileContents = file_get_contents($this->controllerFileName);
        $lastClosingBracePosition = strrpos($fileContents, '}');

        $classDefinition = substr($fileContents, 0, $lastClosingBracePosition);
        $replacement = $method->generate();
        $modifiedContents = $classDefinition . $replacement . substr($fileContents, $lastClosingBracePosition);
        file_put_contents($this->controllerFileName, $modifiedContents);
        
        $converter = new \Laminas\Filter\Word\CamelCaseToDash();
        $dashedName = strtolower($converter->filter(lcfirst($this->controllerName)));
        $viewDirectory = $this->modulePath . '/../view/'.$converter->filter(lcfirst($this->moduleName)).'/'.$dashedName;
        if(!is_dir($viewDirectory)){
            mkdir($viewDirectory);
        }
        
        $viewTemplateName = strtolower($converter->filter(lcfirst(str_replace('Action', '', $this->actionName)))).".phtml";
        file_put_contents($viewDirectory.'/'.$viewTemplateName, "<h1>".$this->controllerName
                . "<h2>".$this->actionName."</h2>"
                . "</h1>");
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function setControllerName(string $controllerName) {
        $this->controllerName = $controllerName . (str_contains($controllerName, 'Controller') ? '' : 'Controller');
    }

    /**
     * {@inheritdoc}
     */
    public function setModuleName(string $moduleName) {
        $this->moduleName = $moduleName;
    }

    private function validate() {
        if (!PSR4Tools::doesModuleExists($this->moduleName)) {
            throw new \Exception('Module with name ' . '"' . $this->moduleName . '" doesnot exists !');
        }
    }

    public function setActionBody(string $body) {
        return;
    }

    public function setActionName(string $actionName) {
        if (!str_contains($actionName, 'Action')) {
            $actionName .= 'Action';
        }
        $this->actionName = $actionName;
    }
}
