<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;
//use Graph\Search;
//use Graph\DepthFirstPaths;
//use Graph\BreadthFirstPaths;
use Graph\Graph;

require_once '../Graph/Graph.php';
//require_once '../Graph/DepthFirstPaths.php';
//require_once '../Graph/BreadthFirstPaths.php';
//require_once '../Sort/Queue.php';
//require_once '../Graph/Search.php';

exampleCC();

function exampleCC()
{
    $v = 5;
    $array = [
        [0, 1],
        [1, 2],
        [2, 3],
        [3, 4],
        [1, 4],
    ];

    $graph = new Graph($array, $v);

    $cc = new CC($graph);
    $M = $cc->count();
    echo $M . 'components';

    $components = [];
    for ($v = 0; $v < $graph->V(); $v++){
        $components[$cc->id($v)] = $v;
    }

    foreach ($components as $vertexes){
        foreach ($vertexes as $vertex){
            echo $vertex . ' ';
        }
        echo '<br/>';
    }

    echo '<pre>';
//    print_r($testSearch->adg);
    echo '<pre>';
}


/**
 * 图
 */
class CC
{
    public function __construct($graph)
    {

    }
}



















