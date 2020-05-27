<?php
namespace Graph;

ini_set("display_errors", "On");
ini_set("html_errors", "On");
use Sort\Queue;

require_once '../Sort/Queue.php';

//example();

function SearchExample()
{
    $v = 5;
    $array = [
        [0, 1],
        [1, 2],
        [2, 3],
        [3, 4],
    ];

    $search = new Search($v, $array);

    echo '<pre>';
//    print_r($search->adg);
    echo '<pre>';
}


/**
 * 图
 */
class Search
{
    private $G; // 图
    private $s; // 顶点


    public function __construct($G, $s)
    {
        $this->G = $G;
        $this->s = $s;

    }

    /**
     * 顶点s是否与$v相连
     * @param $V
     * @return bool
     */
    public function marked($V){
        return true;
    }

    /**
     * 与顶点s相连的顶点总数
     */
    public function count(){

    }
}
