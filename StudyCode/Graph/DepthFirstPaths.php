<?php
namespace Graph;
ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;
//use Graph\Search;
use Sort\Stack;

require_once '../Sort/Stack.php';
//require_once '../Graph/Graph.php';
//require_once '../Graph/Search.php';

//example();

function DepthFirstSearchExample()
{
//    $v = 5;
/*    $array = [
        [0, 1],
        [1, 2],
        [2, 3],
        [3, 4],
    ];*/

//    new Te    stSearch($array, $v, 1);

    echo '<pre>';
//    print_r($testSearch->adg);
    echo '<pre>';
}


/**
 * 图
 */
class DepthFirstPaths
{
    private $marked = [];
    private $edgeTo = []; // 从起点到任意顶点的路径上的最后一个顶点
    private $s;

    public function __construct(Graph $graph,int $search)
    {
        $this->s = $search;
        $this->dfs($graph, $search);
    }

    private function dfs(Graph $graph, $vertex){
        $this->marked[$vertex] = true;

        $adgVertexList = $graph->adg($vertex);
        foreach ($adgVertexList as $w){
            if (empty($this->marked[$w])){
                $this->edgeTo[$w] = $vertex;
                $this->dfs($graph, $w);
            }
        }
    }

    public function hasPathTO($v){
        return $this->edgeTo[$v];
    }

    public function pathTo($v){
        if (!$this->hasPathTO($v)){
            return false;
        }

        $stack = new Stack();
        for($vertex = )
    }
}
