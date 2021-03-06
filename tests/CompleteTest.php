<?php

namespace Graphp\Tests\Algorithms;

use Graphp\Algorithms\Complete as AlgorithmComplete;
use Graphp\Graph\Graph;

class CompleteTest extends TestCase
{
    public function testGraphEmptyK0()
    {
        $graph = new Graph();

        $alg = new AlgorithmComplete($graph);

        $this->assertTrue($alg->isComplete());
    }

    public function testGraphSingleTrivialK1()
    {
        $graph = new Graph();
        $graph->createVertex(1);

        $alg = new AlgorithmComplete($graph);

        $this->assertTrue($alg->isComplete());
    }

    public function testGraphSimplePairK2()
    {
        // 1 -- 2
        $graph = new Graph();
        $graph->createEdgeUndirected($graph->createVertex(1), $graph->createVertex(2));

        $alg = new AlgorithmComplete($graph);

        $this->assertTrue($alg->isComplete());
    }

    public function testGraphSingleDirectedIsNotComplete()
    {
        // 1 -> 2
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex(1), $graph->createVertex(2));

        $alg = new AlgorithmComplete($graph);

        $this->assertFalse($alg->isComplete());
    }

    public function testAdditionalEdgesToNotAffectCompleteness()
    {
        // 1 -> 2
        // 1 -- 2
        // 2 -> 1
        // 1 -> 1
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex(1), $graph->createVertex(2));
        $graph->createEdgeUndirected($graph->getVertex(1), $graph->getVertex(2));
        $graph->createEdgeDirected($graph->getVertex(2), $graph->getVertex(1));
        $graph->createEdgeDirected($graph->getVertex(1), $graph->getVertex(1));

        $alg = new AlgorithmComplete($graph);

        $this->assertTrue($alg->isComplete());
    }
}
