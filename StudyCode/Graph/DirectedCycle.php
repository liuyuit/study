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

    $directedCycle = new DirectedCycle();
    $directedCycle->searchVertex($digraph, 3);

    for($v = 0; $v < $digraph->V(); $v++){
        if ($directedCycle->marked($v)){
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
class DirectedCycle
{
    public $marked = []; // 标记所有从起点能够访问到的顶点
    private $edgeTo = []; //
    private $cycle = [];    // 有向环的所有顶点
    private $onStack = [];  // 递归调用的栈上的所有顶点

    public function __construct(Digraph $digraph, $vertex){
        for ($i = 0; $i < $digraph->V(); $i++){
            if()
        }

        $this->dfs($digraph, $vertex);
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