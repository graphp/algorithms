<?php

use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Set\Vertices;
use Graphp\Algorithms\SimpleCycles;

class SimpleCyclesTest extends TestCase
{
    public function providerGetSimpleCycles()
    {
        return array(
            "simple cycle" => array(
                "edges" => array(
                    array(8, 9),
                    array(9, 8),
                ),
                "cycles" => array(
                    array(8, 9),
                ),
            ),
            "strongly connected component (isolated)" => array(
                "edges" => array(
                    array(1, 2),
                    array(1, 5),
                    array(2, 3),
                    array(3, 1),
                    array(3, 2),
                    array(3, 4),
                    array(3, 6),
                    array(4, 5),
                    array(5, 2),
                    array(6, 4),
                ),
                "cycles" => array(
                    array(1, 2, 3),
                    array(1, 5, 2, 3),
                    array(2, 3),
                    array(2, 3, 4, 5),
                    array(2, 3, 6, 4, 5),
                ),
            ),
            "strongly connected component (connected)" => array(
                "edges" => array(
                    array(1, 2),
                    array(1, 5),
                    array(1, 8),
                    array(2, 3),
                    array(2, 7),
                    array(2, 9),
                    array(3, 1),
                    array(3, 2),
                    array(3, 4),
                    array(3, 6),
                    array(4, 5),
                    array(5, 2),
                    array(6, 4),
                    array(8, 9),
                    array(9, 8),
                ),
                "cycles" => array(
                    array(8, 9),
                    array(1, 2, 3),
                    array(1, 5, 2, 3),
                    array(2, 3),
                    array(2, 3, 4, 5),
                    array(2, 3, 6, 4, 5),
                ),
            ),
        );
    }



    /**
     * @dataProvider providerGetSimpleCycles
     */
    public function testGetSimpleCycles(array $edges, array $cycles)
    {
        $graph = new Graph();
        foreach ($edges as $edge) {
            $graph->createVertex($edge[0], true)->createEdgeTo($graph->createVertex($edge[1], true));
        }
        $alg = new SimpleCycles();
        $actual = array();
        foreach ($alg->getSimpleCycles($graph) as $cycle) {
            $actual[] = $cycle->getIds();
        }
        $this->assertSame($cycles, $actual);
    }
}