<?php

use Graphp\Algorithms\Directed as AlgorithmDirected;
use Graphp\Graph\Graph;

class DirectedTest extends TestCase
{
    public function testGraphEmpty()
    {
        $graph = new Graph();

        $alg = new AlgorithmDirected($graph);

        $this->assertFalse($alg->hasDirected());
        $this->assertFalse($alg->hasUndirected());
        $this->assertFalse($alg->isMixed());
    }

    public function testGraphUndirected()
    {
        // 1 -- 2
        $graph = new Graph();
        $graph->createEdgeUndirected($graph->createVertex(1), $graph->createVertex(2));

        $alg = new AlgorithmDirected($graph);

        $this->assertFalse($alg->hasDirected());
        $this->assertTrue($alg->hasUndirected());
        $this->assertFalse($alg->isMixed());
    }

    public function testGraphDirected()
    {
        // 1 -> 2
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex(1), $graph->createVertex(2));

        $alg = new AlgorithmDirected($graph);

        $this->assertTrue($alg->hasDirected());
        $this->assertFalse($alg->hasUndirected());
        $this->assertFalse($alg->isMixed());
    }

    public function testGraphMixed()
    {
        // 1 -- 2 -> 3
        $graph = new Graph();
        $graph->createEdgeUndirected($graph->createVertex(1), $graph->createVertex(2));
        $graph->createEdgeDirected($graph->getVertex(2), $graph->createVertex(3));

        $alg = new AlgorithmDirected($graph);

        $this->assertTrue($alg->hasDirected());
        $this->assertTrue($alg->hasUndirected());
        $this->assertTrue($alg->isMixed());
    }
}
