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
 * 广度优先搜索
 * 就像一队人从起点开始不断的地探索，每当遇到分岔路口就开始分成多队去分别探索所有的路口
 *
 * 一级一级地去探索，从起点开始分别去访问所有的邻接顶点
 * 这一级所有顶点都访问完了之后再去访问下一级的所有未被访问的邻接顶点
 *
 */
class BreadthFirstPaths
{
    private $marked = [];   // 用于标记某个顶点是否已经访问过
    private $edgeTo = [];   // 从起点到任意顶点的路径上的最后一个顶点
    private $s; // 起点

    /**
     * BreadthFirstPaths constructor.
     * @param Graph $graph 一幅图
     * @param int $search 搜索的起始顶点
     */
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
