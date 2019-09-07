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

exampleCC();

function exampleCC()
{
    $v = 9;
    $array = [
        [0, 1],
        [1, 2],
        [2, 3],
        [3, 4],
        [1, 4],
        [4, 0],
        [5, 6],
        [6, 7],
        [7, 8],
    ];

    $graph = new Graph($array, $v);

    $cc = new CC($graph);
    $M = $cc->count();
    echo $M . 'components';
    echo '<br/>';

    $components = [];
    for ($v = 0; $v < $graph->V(); $v++){
        $components[$cc->id($v)][] = $v;
    }

    foreach ($components as $vertexes){
        foreach ($vertexes as $vertex){
            echo $vertex . ' ';
        }
        echo '<br/>';
    }

    echo '<pre>';
//    print_r($testSearch->adg);
    echo '<pre>';
}


/**
 * 图
 */
class CC
{
    private $marked = [];
    private $id = [];
    private $count = 0;

    public function __construct(Graph $graph)
    {
        for ($s = 0; $s < $graph->V(); $s++){
            if (empty($this->marked[$s])){
                $this->dfs($graph, $s);
                $this->count++;
            }
        }
    }

    public function dfs(Graph $graph, int $v){
        $this->marked[$v] = true; // 将该顶点标记为已访问
        $this->id[$v] = $this->count;

        $adgVertexes = $graph->adg($v);
        foreach ($adgVertexes as $adgVertex){
            if (empty($this->marked[$adgVertex])){
                $this->dfs($graph, $adgVertex);
            }
        }
    }

    public function connected($v, $w){
        return $this->id[$v] === $this->id[$w];
    }

    public function id(int $v){
        return $this->id[$v];
    }

    public function count(){
        return $this->count;
    }
}



















