<?php

namespace LaminasScaffold\Abstraction;

interface ConfigWriterInterface {

    /**
     * Write configuration to file
     */
    public function writeConfig();

    /**
     * @param string $fileName
     */
    public function setConfigFileName(string $fileName);

    /**
     * @param array $config
     */
    public function setConfig(array $config);
}
