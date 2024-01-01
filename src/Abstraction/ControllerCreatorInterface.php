<?php
namespace LaminasScaffold\Abstraction;

interface ControllerCreatorInterface {
    /**
     * @param string $moduleName
     */
    public function setModuleName(string $moduleName);

    /**
     * @param string $controllerName
     */
    public function setControllerName(string $controllerName);
    
    /**
     * Create Controller
     */
    public function create();
    
    /**
     * Set true to create factory class for the controller
     * @param bool $useFactory
     */
    public function setUseFactory(bool $useFactory);
}
