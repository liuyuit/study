<?php
namespace Graph;
ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;
//use Graph\Search;
//use Sort\Stack;

//require_once '../Sort/Stack.php';
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
 * 深度优先搜索，通常用于解决连通性的问题
 *
 * 类似于一个人带着绳子去走迷宫
 * 每访问一个邻接顶点就把这个顶点标记为已访问
 * 每次只访问未标记的邻接顶点
 * 如果当前没有未访问的邻接顶点，那就沿着绳子回到上一个顶点，去访问上一个顶点的未被访问的邻接顶点
 *
 * 每次访问一个顶点就在数组edgeTo中记录，用来表示这个顶点于起点连通，表示方式为 这个顶点-》上一个顶点
 */
class DepthFirstPaths
{
    private $marked = [];   //
    private $edgeTo = [];   // 从起点到任意顶点的路径上的最后一个顶点， 后访问到的顶点-》先于键所表示的顶点访问到的顶点
    private $s;

    /**
     * BreadthFirstPaths constructor.
     * @param Graph $graph 一幅图
     * @param int $search 搜索的起始顶点
     */
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
