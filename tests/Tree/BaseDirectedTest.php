<?php

namespace Graphp\Tests\Algorithms\Tree;

use Graphp\Algorithms\Tree\BaseDirected;
use Graphp\Graph\Graph;
use Graphp\Graph\Set\Vertices;
use Graphp\Tests\Algorithms\TestCase;

abstract class BaseDirectedTest extends TestCase
{
    /**
     *
     * @param Graph $graph
     * @return BaseDirected
     */
    abstract protected function createTreeAlg(Graph $graph);

    /**
     * @return Graph
     */
    abstract protected function createGraphNonTree();

    /**
     * @return Graph
     */
    abstract protected function createGraphTree();

    /**
     * @return Graph
     */
    abstract protected function createGraphParallelEdge();

    public function testNullGraph()
    {
        $graph = new Graph();

        $tree = $this->createTreeAlg($graph);
        $this->assertFalse($tree->isTree());
        $this->assertTrue($tree->getVerticesLeaf()->isEmpty());
        $this->assertTrue($tree->getVerticesInternal()->isEmpty());

        return $tree;
    }

    /**
     * @param BaseDirected $tree
     * @depends testNullGraph
     */
    public function testEmptyGraphDoesNotHaveRootVertex(BaseDirected $tree)
    {
        $this->setExpectedException('UnderflowException');
        $tree->getVertexRoot();
    }

    /**
     * @param BaseDirected $tree
     * @depends testNullGraph
     */
    public function testEmptyGraphDoesNotHaveDegree(BaseDirected $tree)
    {
        $this->setExpectedException('UnderflowException');
        $tree->getDegree();
    }

    /**
     * @param BaseDirected $tree
     * @depends testNullGraph
     */
    public function testEmptyGraphDoesNotHaveHeight(BaseDirected $tree)
    {
        $this->setExpectedException('UnderflowException');
        $tree->getHeight();
    }

    public function testGraphTree()
    {
        $graph = $this->createGraphTree();
        $root = $graph->getVertices()->getVertexFirst();

        $nonRoot = $graph->getVertices()->getMap();
        unset($nonRoot[$root->getId()]);
        $nonRoot = new Vertices($nonRoot);

        $c1 = $nonRoot->getVertexFirst();

        $tree = $this->createTreeAlg($graph);

        $this->assertTrue($tree->isTree());
        $this->assertSame($root, $tree->getVertexRoot());
        $this->assertSame($graph->getVertices()->getVector(), $tree->getVerticesSubtree($root)->getVector());
        $this->assertSame($nonRoot->getVector(), $tree->getVerticesChildren($root)->getVector());
        $this->assertSame($nonRoot->getVector(), $tree->getVerticesDescendant($root)->getVector());
        $this->assertSame($nonRoot->getVector(), $tree->getVerticesLeaf()->getVector());
        $this->assertSame(array(), $tree->getVerticesInternal()->getVector());
        $this->assertSame($root, $tree->getVertexParent($c1));
        $this->assertSame(array(), $tree->getVerticesChildren($c1)->getVector());
        $this->assertSame(array(), $tree->getVerticesDescendant($c1)->getVector());
        $this->assertSame(array($c1), $tree->getVerticesSubtree($c1)->getVector());
        $this->assertEquals(2, $tree->getDegree());
        $this->assertEquals(0, $tree->getDepthVertex($root));
        $this->assertEquals(1, $tree->getDepthVertex($c1));
        $this->assertEquals(1, $tree->getHeight());
        $this->assertEquals(1, $tree->getHeightVertex($root));
        $this->assertEquals(0, $tree->getHeightvertex($c1));

        return $tree;
    }

    /**
     *
     * @param BaseDirected $tree
     * @depends testGraphTree
     */
    public function testGraphTreeRootDoesNotHaveParent(BaseDirected $tree)
    {
        $root = $tree->getVertexRoot();

        $this->setExpectedException('UnderflowException');
        $tree->getVertexParent($root);
    }

    public function testNonTree()
    {
        $graph = $this->createGraphNonTree();

        $tree = $this->createTreeAlg($graph);

        $this->assertFalse($tree->isTree());
    }

    public function testNonTreeVertexHasMoreThanOneParent()
    {
        $graph = $this->createGraphNonTree();

        $tree = $this->createTreeAlg($graph);

        $this->setExpectedException('UnexpectedValueException');
        $tree->getVertexParent($graph->getVertex('v3'));
    }

    public function testGraphWithParallelEdgeIsNotTree()
    {
        $graph = $this->createGraphParallelEdge();

        $tree = $this->createTreeAlg($graph);

        $this->assertFalse($tree->isTree());
    }

    public function testGraphWithLoopIsNotTree()
    {
        // v1 -> v1
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex('v1'), $graph->getVertex('v1'));

        $tree = $this->createTreeAlg($graph);

        $this->assertFalse($tree->isTree());
    }

    public function testGraphWithLoopCanNotGetSubgraph()
    {
        // v1 -> v1
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex('v1'), $graph->getVertex('v1'));

        $tree = $this->createTreeAlg($graph);

        $this->setExpectedException('UnexpectedValueException');
        $tree->getVerticesSubtree($graph->getVertex('v1'));
    }

    public function testGraphWithUndirectedEdgeIsNotTree()
    {
        // v1 -- v2
        $graph = new Graph();
        $graph->createEdgeUndirected($graph->createVertex('v1'), $graph->createVertex('v2'));

        $tree = $this->createTreeAlg($graph);

        $this->assertFalse($tree->isTree());
    }

    public function testGraphWithMixedEdgesIsNotTree()
    {
        // v1 -> v2 -- v3 -> v4
        $graph = new Graph();
        $graph->createEdgeDirected($graph->createVertex('v1'), $graph->createVertex('v2'));
        $graph->createEdgeUndirected($graph->getVertex('v2'), $graph->createVertex('v3'));
        $graph->createEdgeDirected($graph->getVertex('v3'), $graph->createVertex('v4'));

        $tree = $this->createTreeAlg($graph);

        $this->assertFalse($tree->isTree());
    }
}
