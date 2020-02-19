<?php

use Graphp\Algorithms\Parallel as AlgorithmParallel;
use Graphp\Graph\Graph;

class ParallelTest extends TestCase
{
    public function testGraphEmpty()
    {
        $graph = new Graph();

        $alg = new AlgorithmParallel($graph);

        $this->assertFalse($alg->hasEdgeParallel());
    }

    public function testDirectedCycleIsNotConsideredParallel()
    {
        // 1 -> 2
        // 2 -> 1
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $e1 = $graph->createEdgeDirected($v1, $v2);
        $e2 = $graph->createEdgeDirected($v2, $v1);

        $alg = new AlgorithmParallel($graph);

        $this->assertFalse($alg->hasEdgeParallel());
        $this->assertEquals(array(), $alg->getEdgesParallelEdge($e1)->getVector());
        $this->assertEquals(array(), $alg->getEdgesParallelEdge($e2)->getVector());
    }

    public function testDirectedParallelEdge()
    {
        // 1 -> 2
        // 1 -> 2
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $e1 = $graph->createEdgeDirected($v1, $v2);
        $e2 = $graph->createEdgeDirected($v1, $v2);

        $alg = new AlgorithmParallel($graph);

        $this->assertTrue($alg->hasEdgeParallel());
        $this->assertEquals(array($e2), $alg->getEdgesParallelEdge($e1)->getVector());
        $this->assertEquals(array($e1), $alg->getEdgesParallelEdge($e2)->getVector());
    }

    public function testMixedParallelEdge()
    {
        // 1 -> 2
        // 1 -- 2
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $e1 = $graph->createEdgeDirected($v1, $v2);
        $e2 = $graph->createEdgeUndirected($v1, $v2);

        $alg = new AlgorithmParallel($graph);

        $this->assertTrue($alg->hasEdgeParallel());
        $this->assertEquals(array($e2), $alg->getEdgesParallelEdge($e1)->getVector());
        $this->assertEquals(array($e1), $alg->getEdgesParallelEdge($e2)->getVector());
    }

    public function testMixedParallelEdgesMultiple()
    {
        // 1 -> 2
        // 1 -> 2
        // 1 -- 2
        // 1 -- 2
        // 2 -> 1
        // 2 -> 1
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $e1 = $graph->createEdgeDirected($v1, $v2);
        $e2 = $graph->createEdgeDirected($v1, $v2);
        $e3 = $graph->createEdgeUndirected($v1, $v2);
        $e4 = $graph->createEdgeUndirected($v1, $v2);
        $e5 = $graph->createEdgeDirected($v2, $v1);
        $e6 = $graph->createEdgeDirected($v2, $v1);

        $alg = new AlgorithmParallel($graph);

        $this->assertTrue($alg->hasEdgeParallel());
        $this->assertEquals(array($e2, $e3, $e4), $alg->getEdgesParallelEdge($e1)->getVector());
        $this->assertEquals(array($e1, $e3, $e4), $alg->getEdgesParallelEdge($e2)->getVector());
        $this->assertEquals(array($e1, $e2, $e4, $e5, $e6), $alg->getEdgesParallelEdge($e3)->getVector());
        $this->assertEquals(array($e1, $e2, $e3, $e5, $e6), $alg->getEdgesParallelEdge($e4)->getVector());
        $this->assertEquals(array($e3, $e4, $e6), $alg->getEdgesParallelEdge($e5)->getVector());
        $this->assertEquals(array($e3, $e4, $e5), $alg->getEdgesParallelEdge($e6)->getVector());
    }

}
