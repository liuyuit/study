<?php
var_dump(11);exit;
ini_set("display_errors", "On");
ini_set("html_errors", "On");

use Graph\Digraph;
use Graph\DirectedDFS;

require_once '../Graph/DiGraph.php';
require_once '../Graph/DirectedDFS.php';

exampleTransitiveClosure();

function exampleTransitiveClosure()
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

    $transitiveClosure = new TransitiveClosure($digraph);

    $v = 2;
    $w = 3;
    $result = $transitiveClosure->reachable($v, $w);
    $result = !empty($result) ? 'connection' : 'not connection';
    echo "{$v} and {$w} {$result}";

}


/**
 * 有向图顶点对的可达性
 */
class TransitiveClosure
{
    private $allDirectedDfs = [];

    public function __construct(Digraph $digraph)
    {
        for ($vertex = 0; $vertex < $digraph->V(); $vertex++){
            $this->allDirectedDfs[$vertex][] = new DirectedDFS($digraph, $vertex);
        }
    }

    public function reachable($v, $w){
        /**@var DirectedDFS $directedDFS*/
        $directedDFS = $this->allDirectedDfs[$v];
        return $directedDFS->marked($w);
    }

}



















