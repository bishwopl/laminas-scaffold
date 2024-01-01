<?php

namespace LaminasScaffold\Abstraction;

interface ActionCreatorInterface {

    /**
     * Create action for a action
     */
    public function create();

    /**
     * @param string $moduleName
     */
    public function setModuleName(string $moduleName);

    /**
     * @param string $controllerName
     */
    public function setControllerName(string $controllerName);

    /**
     * @param string $actionName
     */
    public function setActionName(string $actionName);
    
    /**
     * @param string $body
     */
    public function setActionBody(string $body);
}
