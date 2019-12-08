<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;
//use Graph\Search;
//use Graph\DepthFirstPaths;
//use Graph\BreadthFirstPaths;
use Graph\Digraph;
use Graph\DirectedFirstOrder;

require_once '../Graph/DiGraph.php';
require_once '../Graph/DirectedFirstOrder.php';
//require_once '../Graph/DepthFirstPaths.php';
//require_once '../Graph/BreadthFirstPaths.php';
//require_once '../Sort/Queue.php';
//require_once '../Graph/Search.php';

exampleKosaraju();

function exampleKosaraju()
{
    $v = 5;
    $array = [
        [0, 1],
        [1, 2],
        [2, 3],
        [3, 4],
        [4, 2],
    ];

    $digraph = new Digraph($v);
    foreach ($array as $adgVertexes) {
        $digraph->addEdge($adgVertexes[0], $adgVertexes[1]);
    }

    $cc = new KosarajuSCC($digraph);
    $M = $cc->count();
    echo $M . ' components';
    echo '<br/>';

    $components = [];
    for ($v = 0; $v < $digraph->V(); $v++){
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
class KosarajuSCC
{
    private $marked = [];
    private $id = [];
    private $count = 0;

    public function __construct(Digraph $digraph)
    {
        $order = new DirectedFirstOrder($digraph->reverse());

        foreach ($order->reversePost() as $vertex){
            if (empty($this->marked[$vertex])){
                $this->dfs($digraph, $vertex);
                $this->count++;
            }
        }
    }

    public function dfs(Digraph $digraph, int $v){
        $this->marked[$v] = true; // 将该顶点标记为已访问
        $this->id[$v] = $this->count;

        $adgVertexes = $digraph->adg($v);
        foreach ($adgVertexes as $adgVertex){
            if (empty($this->marked[$adgVertex])){
                $this->dfs($digraph, $adgVertex);
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



















