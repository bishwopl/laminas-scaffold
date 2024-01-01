<?php

namespace LaminasScaffold\Service;

class DirectoryTree {

    /**
     * Creates directory from array
     * @param array $tree
     * @param string $baseDir
     * @return void
     */
    public static function createDirectorySturcture($tree, $baseDir) {
        foreach ($tree as $key => $value) {
            if (is_array($value)) {
                if (!is_dir($baseDir . '/' . $key)) {
                    mkdir($baseDir . '/' . $key);
                }
                self::createDirectorySturcture($value, $baseDir . '/' . $key);
            } else {
                mkdir($baseDir . '/' . $value);
            }
        }
        return;
    }

}
