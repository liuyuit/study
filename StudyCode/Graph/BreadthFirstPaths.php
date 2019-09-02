<?php
namespace Graph;
ini_set("display_errors", "On");
ini_set("html_errors", "On");
use Sort\Queue;
//use Graph\Search;
//use Sort\Stack;

require_once '../Sort/Queue.php';
//require_once '../Sort/Stack.php';
//require_once '../Graph/Graph.php';
//require_once '../Graph/Search.php';

//example();

function BreadthFirstSearchExample()
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
class BreadthFirstPaths
{
    private $marked = [];   // 用于标记某个顶点是否已经访问过
    private $edgeTo = [];   // 从起点到任意顶点的路径上的最后一个顶点
    private $s; // 起点

    public function __construct(Graph $graph,int $search)
    {
        $this->s = $search;
        $this->bfs($graph, $search);
    }

    private function bfs(Graph $graph, $vertex){
        $this->marked[$vertex] = true;
        $vertexQueue = new queue(); // 用于存储已经访问过但还未遍历其邻接表的顶点。
        $vertexQueue->enQueue($vertex); // 将起点加入队列中

        while(!$vertexQueue->isEmpty()){
            $vertex = $vertexQueue->deQueue();  // 将要遍历这个顶点的邻接顶点，所以将其从队列中删除
            $adgVertexList = $graph->adg($vertex);

            foreach ($adgVertexList as $w){
                if (empty($this->marked[$w])){
                    $this->edgeTo[$w] = $vertex;
                    $this->marked[$w] = true;
                    $vertexQueue->enQueue($w);
                }
            }
        }
    }

    public function hasPathTo($v){
        return isset($this->edgeTo[$v]);
    }

    public function pathTo($v){
        if (!$this->hasPathTo($v)){
            return false;
        }

        $vertexPaths = [];
        array_unshift($vertexPaths, $v);
        while(isset($this->edgeTo[$v])){
            $v = $this->edgeTo[$v];
            array_unshift($vertexPaths, $v);
        }

        return $vertexPaths;
    }
}
