<?php

namespace Graphp\Tests\Algorithms\MaximumMatching;

use Graphp\Algorithms\MaximumMatching\Flow;
use Graphp\Graph\Graph;
use Graphp\Tests\Algorithms\TestCase;

class FlowTest extends TestCase
{
//     /**
//      * run algorithm with small graph and check result against known result
//      */
//     public function testKnownResult()
//     {
//         $loader = new EdgeListBipartit(PATH_DATA . 'Matching_100_100.txt');
//         $loader->setEnableDirectedEdges(false);
//         $graph = $loader->createGraph();

//         $alg = new Flow($graph);
//         $this->assertEquals(100, $alg->getNumberOfMatches());
//     }

    public function testSingleEdge()
    {
        $graph = new Graph();
        $edge = $graph->createEdgeUndirected($graph->createVertex(0)->setGroup(0), $graph->createVertex(1)->setGroup(1));

        $alg = new Flow($graph);
        // correct number of edges
        $this->assertEquals(1, $alg->getNumberOfMatches());
        // actual edge instance returned
        $this->assertEquals(array($edge), $alg->getEdges()->getVector());

        // check
        $flowgraph = $alg->createGraph();
        $this->assertInstanceOf('Graphp\Graph\Graph', $flowgraph);
    }

    /**
     * expect exception for directed edges
     */
    public function testInvalidDirected()
    {
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex(0)->setGroup(0), $graph->createVertex(1)->setGroup(1));

        $alg = new Flow($graph);

        $this->setExpectedException('UnexpectedValueException');
        $alg->getNumberOfMatches();
    }

    /**
     * expect exception for non-bipartit graphs
     */
    public function testInvalidBipartit()
    {
        $graph = new Graph();
        $graph->createEdgeUndirected($graph->createVertex(0)->setGroup(1), $graph->createVertex(1)->setGroup(1));

        $alg = new Flow($graph);

        $this->setExpectedException('UnexpectedValueException');
        $alg->getNumberOfMatches();
    }
}
