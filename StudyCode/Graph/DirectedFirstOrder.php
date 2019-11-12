<?php
namespace Graph;

ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;
//use Graph\Digraph;

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

    $digraph = new Digraph($v);
    foreach ($array as $adgVertexes){
        $digraph->addEdge($adgVertexes[0], $adgVertexes[1]);
    }

    $directedDFS = new DirectedDFS();
    $directedDFS->searchVertex($digraph, 3);

    for($v = 0; $v < $digraph->V(); $v++){
        if ($directedDFS->marked($v)){
            echo $v . "\n";
        }
    }

//    echo '<pre>';
//    print_r($digraph->adg);
//    print_r($directedDFS->marked);

//    echo '<pre>';
}


/**
 * 图
 */
class DirectedFirstOrder
{
    public $marked = []; // 标记所有从起点能够访问到的顶点


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

    public function marked($vertex){
        return !empty($this->marked[$vertex]);
    }
}