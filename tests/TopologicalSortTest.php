<?php

namespace Graphp\Tests\Algorithms;

use Graphp\Algorithms\TopologicalSort;
use Graphp\Graph\Graph;

class TopologicalSortTest extends TestCase
{
    public function testGraphEmpty()
    {
        $graph = new Graph();

        $alg = new TopologicalSort($graph);

        $this->assertInstanceOf('Graphp\Graph\Set\Vertices', $alg->getVertices());
        $this->assertTrue($alg->getVertices()->isEmpty());
    }

    public function testGraphIsolated()
    {
        $graph = new Graph();
        $graph->createVertex(1);
        $graph->createVertex(2);

        $alg = new TopologicalSort($graph);

        $this->assertSame(array($graph->getVertex(1), $graph->getVertex(2)), $alg->getVertices()->getVector());
    }

    public function testGraphSimple()
    {
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex(1), $graph->createVertex(2));

        $alg = new TopologicalSort($graph);

        $this->assertSame(array($graph->getVertex(1), $graph->getVertex(2)), $alg->getVertices()->getVector());
    }

    public function testFailUndirected()
    {
        $graph = new Graph();
        $graph->createEdgeUndirected($graph->createVertex(1), $graph->createVertex(2));

        $alg = new TopologicalSort($graph);

        $this->setExpectedException('UnexpectedValueException');
        $alg->getVertices();
    }

    public function testFailLoop()
    {
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex(1), $graph->getVertex(1));

        $alg = new TopologicalSort($graph);

        $this->setExpectedException('UnexpectedValueException');
        $alg->getVertices();
    }

    public function testFailCycle()
    {
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex(1), $graph->createVertex(2));
        $graph->createEdgeDirected($graph->getVertex(2), $graph->getVertex(1));

        $alg = new TopologicalSort($graph);

        $this->setExpectedException('UnexpectedValueException');
        $alg->getVertices();
    }
}
