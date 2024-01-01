<?php

namespace LaminasScaffold\Abstraction;

interface ModuleCreatorInterface {

    /**
     * @param string $moduleName
     */
    public function setModuleName(string $moduleName) : void;

    /**
     * Creates module
     */
    public function create() : void;

    /**
     * Checks if module exists
     */
    public function doesModuleExists() : bool;
}
