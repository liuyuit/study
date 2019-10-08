<?php
namespace Graph;

ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;

//require_once '../Sort/Queue.php';

digraphExample();

function digraphExample()
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

    echo '<pre>';
    print_r($digraph->adg);
    echo '<pre>';
}


/**
 * 图
 */
class Digraph
{
    private $V = 0; // 顶点数目
    private $E = 0; // 边的数目
    public $adg = [];// 邻接表


    public function __construct($V){
        $this->V = $V;
        $this->E = 0;
        $this->adg = [];

        for ($i = 0; $i < $V; $i++){
            $this->adg[$i] = [];
        }
    }


    public function V(){
        return $this->V;
    }

    public function E(){
        return $this->E;
    }

    /**
     * 添加一条边
     * @param $v // 顶点
     * @param $w // 顶点
     */
    public function addEdge($v, $w){
        $this->adg[$v][] = $w;
        $this->E++;
    }

    public function adg(int $vertex){
        return $this->adg[$vertex];
    }

    public function reverse(){
        $reverseDigraph = new DiGraph($this->V());
        for ($i = 0; $i <= $reverseDigraph->V(); $i++){
            $adgVertexes = $this->adg($i);
            foreach ($adgVertexes as $adgVertex){
                $reverseDigraph->addEdge($adgVertex, $i);
            }
        }

        return $reverseDigraph;
    }
}