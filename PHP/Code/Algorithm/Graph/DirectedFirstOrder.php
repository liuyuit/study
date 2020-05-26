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

    $directedFirstOrder = new DirectedFirstOrder($digraph);


//    echo '<pre>';
    print_r($directedFirstOrder->pre());
    print_r($directedFirstOrder->post());
    print_r($directedFirstOrder->reversePost());
//    print_r($directedDFS->marked);

//    echo '<pre>';
}


/**
 * 图
 */
class DirectedFirstOrder
{
    public $marked = []; // 标记所有已经访问过的顶点
    private $pre = [];  // 顶点的前序排序
    private $post = []; // 后序排序
    private $reversePost = []; // 逆后序排序

    public function __construct(Digraph $digraph)
    {
        for ($i = 0; $i < $digraph->V(); $i ++){
            if (!$this->marked($i)){
                $this->dfs($digraph, $i);
            }
        }
    }

    private function dfs(Digraph $digraph, $vertex)
    {
        $this->pre[] = $vertex;

        $this->marked[$vertex] = true;
        $adgVertexes = $digraph->adg($vertex);
        foreach ($adgVertexes as $adgVertex) {
            if (!$this->marked($adgVertex)) {
                $this->dfs($digraph, $adgVertex);
            }
        }

        $this->post[] = $vertex;
        array_unshift($this->reversePost, $vertex);
    }

    public function marked($vertex)
    {
        return !empty($this->marked[$vertex]);
    }

    public function pre(){
        return $this->pre;
    }

    public function post(){
        return $this->post;
    }

    public function reversePost(){
        return $this->reversePost;
    }
}