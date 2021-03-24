<?php

namespace Graphp\Tests\Algorithms\Property;

use Graphp\Algorithms\Property\WalkProperty;
use Graphp\Graph\Graph;
use Graphp\Graph\Walk;
use Graphp\Tests\Algorithms\TestCase;

class WalkPropertyTest extends TestCase
{
    public function testTrivialGraph()
    {
        $graph = new Graph();
        $v1 = $graph->createVertex(1);

        $walk = Walk::factoryFromEdges(array(), $v1);

        $this->assertCount(1, $walk->getVertices());
        $this->assertCount(0, $walk->getEdges());

        $alg = new WalkProperty($walk);

        $this->assertFalse($alg->isLoop());
        $this->assertFalse($alg->hasLoop());

        $this->assertFalse($alg->isCycle());
        $this->assertFalse($alg->hasCycle());

        $this->assertTrue($alg->isPath());
        $this->assertTrue($alg->isSimple());

        $this->assertTrue($alg->isEulerian());
        $this->assertTrue($alg->isHamiltonian());
    }

    public function testLoop()
    {
        // 1 -- 1
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $e1 = $graph->createEdgeUndirected($v1, $v1);

        $walk = Walk::factoryFromEdges(array($e1), $v1);

        $alg = new WalkProperty($walk);

        $this->assertTrue($alg->isLoop());
        $this->assertTrue($alg->hasLoop());

        $this->assertTrue($alg->isCycle());
        $this->assertTrue($alg->hasCycle());

        $this->assertTrue($alg->isPath());
        $this->assertTrue($alg->isSimple());

        $this->assertTrue($alg->isEulerian());
        $this->assertTrue($alg->isHamiltonian());
    }

    public function testCycle()
    {
        // 1 -- 2 -- 1
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $e1 = $graph->createEdgeUndirected($v1, $v2);
        $e2 = $graph->createEdgeUndirected($v2, $v1);

        $walk = Walk::factoryFromEdges(array($e1, $e2), $v1);

        $this->assertCount(3, $walk->getVertices());
        $this->assertCount(2, $walk->getEdges());

        $alg = new WalkProperty($walk);

        $this->assertTrue($alg->isCycle());
        $this->assertTrue($alg->hasCycle());
        $this->assertTrue($alg->isPath());
        $this->assertTrue($alg->isSimple());

        $this->assertTrue($alg->isEulerian());
        $this->assertTrue($alg->isHamiltonian());
    }

    public function testCircuit()
    {
        // 1 -> 2 -> 1, 2 -> 2
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $e1 = $graph->createEdgeDirected($v1, $v2);
        $e2 = $graph->createEdgeDirected($v2, $v1);
        $e3 = $graph->createEdgeDirected($v2, $v2);

        // 1 -> 2 -> 2 -> 1
        $walk = Walk::factoryFromEdges(array($e1, $e3, $e2), $v1);

        $this->assertEquals(array(1, 2, 2, 1), $walk->getVertices()->getIds());

        $alg = new WalkProperty($walk);

        $this->assertTrue($alg->isCycle());
        $this->assertTrue($alg->isCircuit());
    }

    public function testNonCircuit()
    {
        // 1 -> 2 -> 1, 2 -> 2
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $e1 = $graph->createEdgeDirected($v1, $v2);
        $e2 = $graph->createEdgeDirected($v2, $v1);
        $e3 = $graph->createEdgeDirected($v2, $v2);

        // non-circuit: taking loop twice
        // 1 -> 2 -> 2 -> 2 -> 1
        $walk = Walk::factoryFromEdges(array($e1, $e3, $e3, $e2), $v1);

        $this->assertEquals(array(1, 2, 2, 2, 1), $walk->getVertices()->getIds());

        $alg = new WalkProperty($walk);

        $this->assertTrue($alg->isCycle());
        $this->assertFalse($alg->isCircuit());
    }

    public function testDigon()
    {
        // 1 -> 2 -> 1
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $e1 = $graph->createEdgeDirected($v1, $v2);
        $e2 = $graph->createEdgeDirected($v2, $v1);

        $walk = Walk::factoryFromEdges(array($e1, $e2), $v1);

        $alg = new WalkProperty($walk);

        $this->assertTrue($alg->isDigon());
    }

    public function testTriangle()
    {
        // 1 -> 2 -> 3 -> 1
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $v3 = $graph->createVertex(3);
        $e1 = $graph->createEdgeDirected($v1, $v2);
        $e2 = $graph->createEdgeDirected($v2, $v3);
        $e3 = $graph->createEdgeDirected($v3, $v1);

        $walk = Walk::factoryFromEdges(array($e1, $e2, $e3), $v1);

        $alg = new WalkProperty($walk);

        $this->assertTrue($alg->isTriangle());
    }

    public function testSimplePathWithinGraph()
    {
        // 1 -- 2 -- 2
        $graph = new Graph();
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $graph->createEdgeUndirected($v1, $v2);
        $e2 = $graph->createEdgeUndirected($v2, $v2);

        // only use "2 -- 2" part
        $walk = Walk::factoryFromEdges(array($e2), $v2);

        $this->assertCount(2, $walk->getVertices());
        $this->assertCount(1, $walk->getEdges());

        $alg = new WalkProperty($walk);

        $this->assertTrue($alg->isCycle());
        $this->assertTrue($alg->hasCycle());
        $this->assertTrue($alg->isPath());
        $this->assertTrue($alg->isSimple());

        $this->assertFalse($alg->isEulerian());
        $this->assertFalse($alg->isHamiltonian());
    }
}
