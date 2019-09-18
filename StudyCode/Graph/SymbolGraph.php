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
        return isset($this->st[$s]);
    }

    public function index(string $s){
        return $this->st[$s];
    }

    public function name(int $v){
        return $this->keys[$v];
    }

    public function G(){
        return $this->G;
    }
}
