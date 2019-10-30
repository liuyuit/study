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
    foreach ($array as $adgVertexes) {
        $digraph->addEdge($adgVertexes[0], $adgVertexes[1]);
    }

    $directedCycle = new DirectedCycle($digraph);
//    $directedCycle->searchVertex($digraph, 3);

    for ($v = 0; $v < $digraph->V(); $v++) {
        if ($directedCycle->marked($v)) {
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

    public function __construct(Digraph $digraph)
    {
        for ($i = 0; $i < $digraph->V(); $i++) {
            if (!$this->marked($i)) {
                $this->dfs($digraph, $i);
            }
        }
    }

    private function dfs(Digraph $digraph, $vertex)
    {
        $this->onStack[$vertex] = true;
        $this->marked[$vertex] = true;
        $adgVertexes = $digraph->adg($vertex);
        foreach ($adgVertexes as $adgVertex) {
            if ($this->hasCycle()) { // 已经确定是有环图了
                return;
            } elseif (!$this->marked($adgVertex)) { // 访问到一个没有被标记的顶点
                $this->edgeTo[$adgVertex] = $vertex;
                $this->dfs($digraph, $adgVertex);
            } elseif (!empty($this->onStack[$adgVertex])) { // 访问到一个已经被标记的顶点，并且这个顶点在当前访问的堆栈内，在以$vertex为初始顶点的一次深度搜索中。
//                for ($x = $adgVertexes; ; $x = ){ // TODO

//                }

                $cycle[] = $adgVertex;
                $cycle[] = $vertex;
            }
        }
        $this->onStack[$vertex] = false;
    }

    private function hasCycle()
    {
        return !empty($this->cycle);
    }

    public function marked($vertex)
    {
        return !empty($this->marked[$vertex]);
    }

    public function cycle()
    {
        return $this->cycle;
    }
}