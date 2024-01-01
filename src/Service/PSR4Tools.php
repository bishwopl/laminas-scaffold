<?php

namespace LaminasScaffold\Service;

class PSR4Tools {
    /**
     * @param string $nameSpace
     * @return string
     */
    public static function findPathFromNameSpace($nameSpace){
        $psr4ClassMap = include getcwd().'/vendor/composer/autoload_psr4.php';
        return $psr4ClassMap[$nameSpace.'\\'];
    }
    
    public static function doesModuleExists(string $moduleName){
        return is_dir(getcwd().'/module/'.$moduleName);
    }
}
