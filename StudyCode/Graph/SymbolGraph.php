<?php

namespace Graph;
ini_set("display_errors", "On");
ini_set("html_errors", "On");


class SymbolGraph
{
    private $st = [];   // 符号名-》索引
    private $keys = []; // 索引-》符号名
    private $G;    // 图的引用

    public function __construct($data)
    {
        foreach ($data as $lineData){
            foreach ($lineData as $vertex){     // 为每个不同的字符串关联一个索引
                if (!$this->contains($vertex)){
                    $this->st[$vertex] = count($this->st);
                }
            }
        }

        foreach ($this->st as $name => $key){ // 用来获得顶点名的反向索引是一个数组
            $this->keys[$key] = $name;
        }

        $vertexes = [];
        foreach ($data as $lineData){
            $v = $lineData[0];  // 将每一个行的顶点和该行的其他顶点相连
            for ($i = 1; $i < count($lineData); $i++){

            }
        }
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
