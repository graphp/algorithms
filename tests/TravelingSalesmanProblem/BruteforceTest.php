<?php

use Graphp\Algorithms\TravelingSalesmanProblem\Bruteforce;
use Graphp\Graph\Graph;

class BruteforceTest extends TestCase
{
    public function testGetWeightReturnsExpectedWeightForSimpleCycle()
    {
        $graph = new Graph();
        $a = $graph->createVertex();
        $b = $graph->createVertex();
        $c = $graph->createVertex();
        $graph->createEdgeDirected($a, $b)->setWeight(1);
        $graph->createEdgeDirected($b, $c)->setWeight(2);
        $graph->createEdgeDirected($c, $a)->setWeight(3);

        $alg = new Bruteforce($graph);

        $this->assertEquals(6, $alg->getWeight());
    }

    public function testSetUpperLimitMstSetsExactLimitForSimpleCycle()
    {
        $graph = new Graph();
        $a = $graph->createVertex();
        $b = $graph->createVertex();
        $c = $graph->createVertex();
        $graph->createEdgeDirected($a, $b)->setWeight(1);
        $graph->createEdgeDirected($b, $c)->setWeight(2);
        $graph->createEdgeDirected($c, $a)->setWeight(3);

        $alg = new Bruteforce($graph);
        $alg->setUpperLimitMst();

        $ref = new ReflectionProperty($alg, 'upperLimit');
        $ref->setAccessible(true);

        $this->assertEquals(6, $ref->getValue($alg));
    }
}
