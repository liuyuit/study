<?php

namespace Graph;
ini_set("display_errors", "On");
ini_set("html_errors", "On");


class SymbolGraph
{
    private $st = [];   // 符号名-》索引
    private $keys = []; // 索引-》符号名
    private $G;    // 图的引用

    public function __construct()
    {
    }

    public function contains(string $s): bool
    {

    }

    public function index(string $s){

    }

    public function name(int $v){

    }

    public function G(){
        return $this->G;
    }
}
