<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;
//use Graph\Search;
//use Graph\DepthFirstPaths;
//use Graph\BreadthFirstPaths;
//use Graph\Graph;
use Graph\SymbolGraph;

require_once '../Graph/Graph.php';
require_once '../Graph/SymbolGraph.php';
//require_once '../Graph/DepthFirstPaths.php';
//require_once '../Graph/BreadthFirstPaths.php';
//require_once '../Sort/Queue.php';
//require_once '../Graph/Search.php';

exampleSymbolGraphTest();

function exampleSymbolGraphTest()
{
//    $v = 9;
    $array = [
        ['movie0', 'actor0', 'actor01', 'actor02'],
        ['movie1', 'actor1', 'actor11', 'actor12'],
        ['movie2', 'actor2'],
        ['movie3', 'actor3'],
//        ['movie1', 'actor4'],
//        ['movie4', 'actor0'],
//        ['movie5', 'actor6'],
//        ['movie6', 'actor7'],
//        ['movie7', 'actor8'],
    ];

    new SymbolGraphTest($array);
//    $symbolGraphTest = new SymbolGraphTest($array);
//    echo '<pre>';
//    print_r($testSearch->adg);
//    echo '<pre>';
}


/**
 * 图
 */
class SymbolGraphTest
{

    public function __construct($vertexes)
    {
        $symbolGraph = new SymbolGraph($vertexes);
        $graph = $symbolGraph->G();

        foreach ($vertexes as $vertexLine){
            $source = $vertexLine[0];
            foreach ($graph->adg($symbolGraph->index($source)) as $adgVertex){
//                echo $adgVertex . " ";
                echo $symbolGraph->name($adgVertex) . " ";
            }
            echo "\n";
        }
//        var_dump($graph);
    }
}



















