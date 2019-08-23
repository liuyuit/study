<?php
namespace Graph;
ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;
//use Graph\Search;
use Graph\Graph;

//require_once '../Sort/Queue.php';
//require_once '../Graph/Graph.php';
//require_once '../Graph/Search.php';

//example();

function DepthFirstSearchExample()
{
//    $v = 5;
/*    $array = [
        [0, 1],
        [1, 2],
        [2, 3],
        [3, 4],
    ];*/

//    new Te    stSearch($array, $v, 1);

    echo '<pre>';
//    print_r($testSearch->adg);
    echo '<pre>';
}


/**
 * 图
 */
class DepthFirstSearch
{
    private $marked = [];
    private $count;

    public function __construct(Graph $graph,int $search)
    {
        $this->dfs($graph, $search);
    }

    private function dfs(Graph $graph, $vertex){
        $this->marked[$vertex] = true;
        $this->count++;

        $adgArr = $graph->adg($vertex);
        foreach ($adgArr as $w){
            if (empty($this->marked[$w])){
                $this->dfs($graph, $w);
            }
        }
    }

    public function marked($w){
        return $this->marked[$w];
    }

    public function count(){
        return $this->count;
    }
}
