<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");

use Graph\Digraph;
use Graph\DirectedDFS;

require_once '../Graph/DiGraph.php';
require_once '../Graph/DirectedDFS.php';

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

    $transitiveClosure = new TransitiveClosure($digraph);
}


/**
 * 图
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



















