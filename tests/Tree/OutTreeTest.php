<?php

namespace Graphp\Tests\Algorithms\Tree;

use Graphp\Algorithms\Tree\OutTree;
use Graphp\Graph\Graph;

class OutTreeTest extends BaseDirectedTest
{
    protected function createGraphTree()
    {
        // c1 <- root -> c2
        $graph = new Graph();
        $root = $graph->createVertex();

        $c1 = $graph->createVertex();
        $graph->createEdgeDirected($root, $c1);

        $c2 = $graph->createVertex();
        $graph->createEdgeDirected($root, $c2);

        return $graph;
    }

    protected function createTreeAlg(Graph $graph)
    {
        return new OutTree($graph);
    }

    protected function createGraphNonTree()
    {
        // v1 -> v3 <- v2 -> v4
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex('v1'), $graph->createVertex('v3'));
        $graph->createEdgeDirected($graph->createVertex('v2'), $graph->getVertex('v3'));
        $graph->createEdgeDirected($graph->getVertex('v2'), $graph->createVertex('v4'));

        return $graph;
    }

    protected function createGraphParallelEdge()
    {
        // v1 -> v2, v1 -> v2
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex('v1'), $graph->createVertex('v2'));
        $graph->createEdgeDirected($graph->getVertex('v1'), $graph->getVertex('v2'));

        return $graph;
    }
}
