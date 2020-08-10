<?php

namespace Graphp\Tests\Algorithms;

use Graphp\Algorithms\Symmetric as AlgorithmSymmetric;
use Graphp\Graph\Graph;

class SymmetricTest extends TestCase
{
    public function testGraphEmpty()
    {
        $graph = new Graph();

        $alg = new AlgorithmSymmetric($graph);

        $this->assertTrue($alg->isSymmetric());
    }

    public function testGraphIsolated()
    {
        $graph = new Graph();
        $graph->createVertex(1);
        $graph->createVertex(2);

        $alg = new AlgorithmSymmetric($graph);

        $this->assertTrue($alg->isSymmetric());
    }

    public function testGraphSingleArcIsNotSymmetricr()
    {
        // 1 -> 2
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex(1), $graph->createVertex(2));

        $alg = new AlgorithmSymmetric($graph);

        $this->assertFalse($alg->isSymmetric());
    }

    public function testGraphAntiparallelIsSymmetricr()
    {
        // 1 -> 2 -> 1
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex(1), $graph->createVertex(2));
        $graph->createEdgeDirected($graph->getVertex(2), $graph->getVertex(1));

        $alg = new AlgorithmSymmetric($graph);

        $this->assertTrue($alg->isSymmetric());
    }

    public function testGraphSingleUndirectedIsSymmetricr()
    {
        // 1 -- 2
        $graph = new Graph();
        $graph->createEdgeUndirected($graph->createVertex(1), $graph->createVertex(2));

        $alg = new AlgorithmSymmetric($graph);

        $this->assertTrue($alg->isSymmetric());
    }
}
