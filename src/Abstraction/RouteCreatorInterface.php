<?php

namespace LaminasScaffold\Abstraction;

interface RouteCreatorInterface{

    /**
     * Creates the route
     */
    public function create();

    /**
     * @param string $route
     */
    public function setRoute(string $route);

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
}
