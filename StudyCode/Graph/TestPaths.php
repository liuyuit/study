<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;
//use Graph\Search;
use Graph\DepthFirstPaths;
use Graph\Graph;

require_once '../Graph/Graph.php';
require_once '../Graph/DepthFirstPaths.php';
//require_once '../Sort/Queue.php';
//require_once '../Graph/Search.php';

example();

function example()
{
    $v = 5;
    $array = [
        [0, 1],
        [1, 2],
        [2, 3],
        [3, 4],
        [1, 4],
    ];

    new TestPaths($array, $v, 1);

    echo '<pre>';
//    print_r($testSearch->adg);
    echo '<pre>';
}


/**
 * 图
 */
class TestPaths
{
    public function __construct(array $vertexArr,int $vertexNum, $searchVertex)
    {
        $graph = new Graph($vertexArr, $vertexNum);
        $search = new DepthFirstPaths($graph, $searchVertex);

        for ($v = 0; $v < $graph->V(); $v++){
            if ($search->hasPathTo($v)){
                echo $searchVertex . 'TO' . $v .':     ';
                $paths = $search->pathTo($v);
                foreach ($paths as $value){
                    if($value == $searchVertex){
                        echo $value;
                    }else{
                        echo '-' . $value;
                    }
                }
                echo "<br/>";
            }
        }
    }
}



















