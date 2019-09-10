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

exampleTwoColor();

function exampleTwoColor()
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

    $graph = new Graph($array, $v);

    $cc = new TwoColor($graph);
    if ($cc->hasCycle()){
        echo 'has cycle';
    } else {
        echo 'not cycle';
    }


//    echo '<pre>';
//    print_r($testSearch->adg);
//    echo '<pre>';
}


/**
 * 图
 */
class TwoColor
{
    private $marked = [];
    private $
    private $hasCycle = false;

    public function __construct(Graph $graph)
    {
        for ($s = 0; $s < $graph->V(); $s++){
            if (empty($this->marked[$s])){
                $this->dfs($graph, $s, $s);
            }
        }
    }

    public function dfs(Graph $graph, int $v, int $u){
        $this->marked[$v] = true; // 将该顶点标记为已访问

        $adgVertexes = $graph->adg($v);
        foreach ($adgVertexes as $adgVertex){
            if (empty($this->marked[$adgVertex])){
                $this->dfs($graph, $adgVertex, $v);
            }elseif ($adgVertex != $u){
                // 遍历到了一个已被标记的顶点，并且这个顶点不是上一个递归访问的顶点，
                // 这个顶点在两条路线上被遍历到，所以这是个有环图
                $this->hasCycle = true;
            }
        }
    }

    public function hasCycle(){
        return $this->hasCycle;
    }
}



















