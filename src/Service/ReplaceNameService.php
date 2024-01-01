<?php

namespace LaminasScaffold\Service;

/**
 * Replace default names in config array, directory, filenames, file contents etc 
 */
class ReplaceNameService {

    /**
     * Search term
     * @var string
     */
    private $search;

    /**
     * Replace term
     * @var string
     */
    private $replace;

    /**
     * Prepare the object with search term and replace term
     * @param string $search 
     * @param string $replace
     */
    public function __construct(string $search = NULL, string $replace = NULL) {
        $this->search = $search;
        $this->replace = $replace;
    }

    /**
     * @param string $search
     * @return void
     */
    public function setSearch(string $search): void {
        $this->search = $search;
    }

    /**
     * @param string $replace
     * @return void
     */
    public function setReplace(string $replace): void {
        $this->replace = $replace;
    }

    /**
     * @param array $a
     */
    public static function replaceFromArray($a, string $search,string $replace) {
        return json_decode(str_replace($search, $replace,json_encode($a)), true);
    }
}
