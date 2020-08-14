<?php

namespace Graphp\Tests\Algorithms;

use Graphp\Algorithms\ConnectedComponents as AlgorithmConnected;
use Graphp\Graph\Graph;

class ConnectedComponentsTest extends TestCase
{
    public function testNullGraph()
    {
        $graph = new Graph();

        $alg = new AlgorithmConnected($graph);

        $this->assertEquals(0, $alg->getNumberOfComponents());
        $this->assertFalse($alg->isSingle());
        $this->assertCount(0, $alg->createGraphsComponents());
    }

    public function testGraphSingleTrivial()
    {
        $graph = new Graph();
        $graph->createVertex(1);

        $alg = new AlgorithmConnected($graph);

        $this->assertEquals(1, $alg->getNumberOfComponents());
        $this->assertTrue($alg->isSingle());

        $graphs = $alg->createGraphsComponents();

        $this->assertCount(1, $graphs);
        $this->assertGraphEquals($graph, \reset($graphs));
    }

    public function testGraphEdgeDirections()
    {
        // 1 -- 2 -> 3 <- 4
        $graph = new Graph();
        $graph->createEdgeUndirected($graph->createVertex(1), $graph->createVertex(2));
        $graph->createEdgeDirected($graph->getVertex(2), $graph->createVertex(3));
        $graph->createEdgeDirected($graph->createVertex(4), $graph->getVertex(3));

        $alg = new AlgorithmConnected($graph);

        $this->assertEquals(1, $alg->getNumberOfComponents());
        $this->assertTrue($alg->isSingle());

        $graphs = $alg->createGraphsComponents();

        $this->assertCount(1, $graphs);
        $this->assertGraphEquals($graph, \reset($graphs));
        $this->assertGraphEquals($graph, $alg->createGraphComponentVertex($graph->getVertex(1)));
    }

    public function testComponents()
    {
        // 1 -- 2, 3 -> 4, 5
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $v3 = $graph->createVertex(3);
        $v4 = $graph->createVertex(4);
        $v5 = $graph->createVertex(5);
        $graph->createEdgeUndirected($v1, $v2);
        $graph->createEdgeDirected($v3, $v4);

        $alg = new AlgorithmConnected($graph);

        $this->assertEquals(3, $alg->getNumberOfComponents());
        $this->assertFalse($alg->isSingle());

        $graphs = $alg->createGraphsComponents();
        $this->assertCount(3, $graphs);

        $ge = new Graph();
        $ge->createEdgeUndirected($ge->createVertex(1), $ge->createVertex(2));
        $this->assertGraphEquals($ge, $alg->createGraphComponentVertex($v2));

        $ge = new Graph();
        $ge->createVertex(5);
        $this->assertEquals($ge, $alg->createGraphComponentVertex($v5));
    }

    public function testInvalidVertexPassedToAlgorithm()
    {
        $graph = new Graph();

        $graph2 = new Graph();
        $v2 = $graph2->createVertex(12);

        $alg = new AlgorithmConnected($graph);

        $this->setExpectedException('InvalidArgumentException');
        $alg->createGraphComponentVertex($v2);
    }
}
