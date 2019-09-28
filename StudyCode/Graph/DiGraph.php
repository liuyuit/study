<?php
namespace Graph;

ini_set("display_errors", "On");
ini_set("html_errors", "On");
use Sort\Queue;

require_once '../Sort/Queue.php';

// graphExample();

function graphExample()
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
class Graph
{
    private $V = 0; // 顶点数目
    private $E = 0; // 边的数目
    public $adg = [];// 邻接表


    /**
     * @param $V
     * @param $edgeList // 每个元素是一个包含两个顶点的数组
     */
    public function __construct($edgeList, $V){
        $this->initGraph($V);

        foreach ($edgeList as $edge){
            $this->addEdge($edge[0], $edge[1]);
        }
    }

    public function initGraph($V){
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
        $this->adg[$w][] = $v;
        $this->E++;
    }

    public function adg(int $vertex){
        return $this->adg[$vertex];
    }
}

















/**
 * 图
 */
class MyGraph
{
    private $V = 0; // 顶点数目
    private $E = 0; // 边的数目
    public $adg = [];// 邻接表


    public function initGraph($V){
        $this->V = $V;
        $this->E = 0;
        $this->adg = [];

        for ($i = 0; $i < $V; $i++){
            $this->adg[$i] = New Queue();
        }
    }

    /**
     * @param $V
     * @param $edgeList // 每个元素是一个包含两个顶点的数组
     */
    public function createGraph($V, $edgeList){
        $this->initGraph($V);

        foreach ($edgeList as $edge){
            $this->addEdge($edge[0], $edge[1]);
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
        /** @var Queue $vQueue */
        $vQueue = $this->adg[$v];
        $vQueue->enQueue($w);
        /** @var Queue $wQueue */
        $wQueue = $this->adg[$w];
        $wQueue->enQueue($v);
        $this->E++;
    }
}

