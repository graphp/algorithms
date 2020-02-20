<?php

use Graphp\Algorithms\Eulerian as AlgorithmEulerian;
use Graphp\Graph\Graph;

class EulerianTest extends TestCase
{
    public function testGraphEmpty()
    {
        $graph = new Graph();

        $alg = new AlgorithmEulerian($graph);

        $this->assertFalse($alg->hasCycle());
    }

    public function testGraphPairHasNoCycle()
    {
        // 1 -- 2
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $graph->createEdgeUndirected($v1, $v2);

        $alg = new AlgorithmEulerian($graph);

        $this->assertFalse($alg->hasCycle());
    }

    public function testGraphTriangleCycleIsNotBipartit()
    {
        // 1 -- 2 -- 3 -- 1
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $v3 = $graph->createVertex(3);
        $graph->createEdgeUndirected($v1, $v2);
        $graph->createEdgeUndirected($v2, $v3);
        $graph->createEdgeUndirected($v3, $v1);

        $alg = new AlgorithmEulerian($graph);

        $this->assertTrue($alg->hasCycle());
    }
}
