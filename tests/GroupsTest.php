<?php

namespace Graphp\Tests\Algorithms;

use Graphp\Algorithms\Groups as AlgorithmGroups;
use Graphp\Graph\Graph;

class GroupsTest extends TestCase
{
    public function testGraphEmpty()
    {
        $graph = new Graph();

        $alg = new AlgorithmGroups($graph);

        $this->assertEquals(array(), $alg->getGroups());
        $this->assertEquals(0, $alg->getNumberOfGroups());

        $this->assertTrue($alg->getVerticesGroup(123)->isEmpty());

        $this->assertFalse($alg->isBipartit());
    }

    public function testGraphPairIsBipartit()
    {
        // 1 -> 2
        $graph = new Graph();
        $v1 = $graph->createVertex(1)->setGroup(1);
        $v2 = $graph->createVertex(2)->setGroup(2);
        $graph->createEdgeDirected($v1, $v2);

        $alg = new AlgorithmGroups($graph);

        $this->assertEquals(array(1, 2), $alg->getGroups());
        $this->assertEquals(2, $alg->getNumberOfGroups());

        $this->assertTrue($alg->getVerticesGroup(123)->isEmpty());
        $this->assertEquals(array(1 => $v1), $alg->getVerticesGroup(1)->getMap());

        $this->assertTrue($alg->isBipartit());
    }

    public function testGraphTriangleCycleIsNotBipartit()
    {
        // 1 -> 2 -> 3 -> 1
        $graph = new Graph();
        $v1 = $graph->createVertex(1)->setGroup(1);
        $v2 = $graph->createVertex(2)->setGroup(2);
        $v3 = $graph->createVertex(3)->setGroup(1);
        $graph->createEdgeDirected($v1, $v2);
        $graph->createEdgeDirected($v2, $v3);
        $graph->createEdgeDirected($v3, $v1);

        $alg = new AlgorithmGroups($graph);

        $this->assertEquals(array(1, 2), $alg->getGroups());
        $this->assertEquals(2, $alg->getNumberOfGroups());

        $this->assertTrue($alg->getVerticesGroup(123)->isEmpty());
        $this->assertEquals(array(1 => $v1, 3 => $v3), $alg->getVerticesGroup(1)->getMap());

        $this->assertFalse($alg->isBipartit());
    }
}
