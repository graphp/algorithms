<?php

use Graphp\Algorithms\Tree\InTree;
use Graphp\Graph\Graph;

class InTreeTest extends BaseDirectedTest
{
    protected function createGraphTree()
    {
        // c1 -> root <- c2
        $graph = new Graph();
        $root = $graph->createVertex();

        $c1 = $graph->createVertex();
        $graph->createEdgeDirected($c1, $root);

        $c2 = $graph->createVertex();
        $graph->createEdgeDirected($c2, $root);

        return $graph;
    }

    protected function createTreeAlg(Graph $graph)
    {
        return new InTree($graph);
    }

    protected function createGraphNonTree()
    {
        // v1 -> v2 <- v3 -> v4
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex('v1'), $graph->createVertex('v2'));
        $graph->createEdgeDirected($graph->createVertex('v3'), $graph->getVertex('v2'));
        $graph->createEdgeDirected($graph->getVertex('v3'), $graph->createVertex('v4'));

        return $graph;
    }

    protected function createGraphParallelEdge()
    {
        // v1 <- v2, v1 <- v2
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex('v2'), $graph->createVertex('v1'));
        $graph->createEdgeDirected($graph->getVertex('v2'), $graph->getVertex('v1'));

        return $graph;
    }
}
