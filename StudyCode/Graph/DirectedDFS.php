<?php
namespace Graph;

ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;
use Graph\Digraph;
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


    public function searchVertex(Digraph $digraph, $vertex){
        $this->dfs($digraph, $vertex);
    }

    public function searchVertexes(Digraph $digraph, $vertexes){

    }

    private function dfs(Digraph $digraph, $vertex){
        $this->marked[$vertex] = true;
        $adgVertexes = $digraph->adg($vertex);
        foreach ($adgVertexes as $adgVertex){
            if (!$this->marked($adgVertex)){
                $this->dfs($digraph, $adgVertex);
            }
        }
    }

    private function marked($vertex){
        return $this->marked[$vertex];
    }
}