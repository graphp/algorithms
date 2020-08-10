<?php

namespace Graphp\Tests\Algorithms;

use Graphp\Algorithms\Loop as AlgorithmLoop;
use Graphp\Graph\Graph;

class LoopTest extends TestCase
{
    public function testGraphEmpty()
    {
        $graph = new Graph();

        $alg = new AlgorithmLoop($graph);

        $this->assertFalse($alg->hasLoop());
    }

    public function testGraphWithMixedCircuitIsNotConsideredLoop()
    {
        // 1 -> 2
        // 2 -- 1
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $graph->createEdgeDirected($v1, $v2);
        $graph->createEdgeUndirected($v2, $v1);

        $alg = new AlgorithmLoop($graph);

        $this->assertFalse($alg->hasLoop());
        $this->assertFalse($alg->hasLoopVertex($v1));
        $this->assertFalse($alg->hasLoopVertex($v2));
    }

    public function testGraphUndirectedLoop()
    {
        // 1 -- 1
        $graph = new Graph();
        $graph->createEdgeUndirected($graph->createVertex(1), $v1 = $graph->getVertex(1));

        $alg = new AlgorithmLoop($graph);

        $this->assertTrue($alg->hasLoop());
        $this->assertTrue($alg->hasLoopVertex($v1));
    }

    public function testGraphDirectedLoop()
    {
        // 1 -> 1
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex(1), $v1 = $graph->getVertex(1));

        $alg = new AlgorithmLoop($graph);

        $this->assertTrue($alg->hasLoop());
        $this->assertTrue($alg->hasLoopVertex($v1));
    }
}
