<?php
ini_set("display_errors", "On");
ini_set("html_errors", "On");
//use Sort\Queue;
//use Graph\Search;
//use Graph\DepthFirstPaths;
//use Graph\BreadthFirstPaths;
use Graph\Graph;
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
    $v = 9;
    $array = [
        ['movie0', 'actor1'],
        ['movie1', 'actor2'],
        ['movie2', 'actor3'],
        ['movie3', 'actor4'],
//        ['movie1', 'actor4'],
//        ['movie4', 'actor0'],
        ['movie5', 'actor6'],
        ['movie6', 'actor7'],
        ['movie7', 'actor8'],
    ];

    $symbolGraphTest = new SymbolGraphTest($array);
//    echo '<pre>';
//    print_r($testSearch->adg);
//    echo '<pre>';
}


/**
 * 图
 */
class SymbolGraphTest
{
    private $marked = [];
    private $color = [];
    private $isTwoColorable = true;

    public function __construct($vertexes)
    {
        $symbolGraph = new SymbolGraph($vertexes);
        $graph = $symbolGraph->G();


    }
}



















