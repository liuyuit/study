<?php
namespace Graph;

ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;

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
    private $V = 0; // 顶点数目
    private $E = 0; // 边的数目
    public $adg = [];// 邻接表


    public function searchVertex($digraph, $vertex){
        
    }

    public function searchVertexes($digraph, $vertexes){

    }
}