<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");
use Sort\Queue;

//require_once '../Sort/Queue.php';
require_once '../Graph/Graph.php';

example();

function example()
{
    $v = 5;
    $array = [
        [0, 1],
        [1, 2],
        [2, 3],
        [3, 4],
    ];

    $graph = new Graph();
    $graph->createGraph($v, $array);

    echo '<pre>';
    print_r($graph->adg);
    echo '<pre>';
}


/**
 * 图
 */
class TestSearch
{

    public function __construct(array $vertexArr,int $vertexNum, $searchVertex)
    {
        $graph = new Graph($vertexArr, $vertexNum);
        $search = new Search($graph, $searchVertex);

        for ($v = 0; $v < $graph->V(); $v++){
            if ($search->marked($v)){

            }
        }
    }

}
