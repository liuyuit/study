<?php
namespace Graph;

ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;
use Graph\Digraph;

require_once '../Graph/Digraph.php';
//require_once '../Sort/Queue.php';

directedDFSExample();

function directedDFSExample()
{
    $v = 5;
    $array = [
        [0, 1],
        [1, 2],
        [2, 3],
        [3, 4],
    ];

    $graph = new Graph($v, $array);

    echo '<pre>';
    print_r($graph->adg);
    echo '<pre>';
}


/**
 * 图
 */
class DirectedDFS
{
    private $marked = []; // 标记所有从起点能够访问到的顶点


    public function searchVertex($digraph, $vertex){
//        $this
    }

    public function searchVertexes($digraph, $vertexes){

    }

    public function dfs($digraph, $vertex){
        $this->marked[$vertex] = true;
        $adgVertexes = $digraph->
    }
}