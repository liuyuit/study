<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;
//use Graph\Search;
//use Graph\DepthFirstPaths;
//use Graph\BreadthFirstPaths;
use Graph\Graph;

require_once '../Graph/Graph.php';
//require_once '../Graph/DepthFirstPaths.php';
//require_once '../Graph/BreadthFirstPaths.php';
//require_once '../Sort/Queue.php';
//require_once '../Graph/Search.php';

exampleSymbolGraphTest();

function exampleSymbolGraphTest()
{
    $v = 9;
    $array = [
        [0, 1],
        [1, 2],
        [2, 3],
        [3, 4],
//        [1, 4],
//        [4, 0],
        [5, 6],
        [6, 7],
        [7, 8],
    ];

    $graph = new SymbolGraphTest($array, $v);

    $cc = new TwoColor($graph);
    if ($cc->isBipartite()){
        echo 'is bipartite';
    } else {
        echo 'not bipartite';
    }


//    echo '<pre>';
//    print_r($testSearch->adg);
//    echo '<pre>';
}


/**
 * 图
 */
class SymbolGraphTest
{
    private $marked = [];
    private $color = [];
    private $isTwoColorable = true;

    public function __construct(Graph $graph)
    {
        for ($s = 0; $s < $graph->V(); $s++){
            if (empty($this->marked[$s])){
                // 每个连通分量只有一个顶点会进入这个分支
                $this->color[$s] = true;
                $this->dfs($graph, $s);
            }
        }
    }
}



















