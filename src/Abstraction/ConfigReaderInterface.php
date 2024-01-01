<?php

namespace LaminasScaffold\Abstraction;

interface ConfigReaderInterface {

    /**
     * Read configuration from file
     */
    public function readConfig() : array;

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName);
}
