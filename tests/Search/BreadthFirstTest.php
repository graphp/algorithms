<?php

namespace Graphp\Tests\Algorithms\Search;

use Graphp\Algorithms\Search\BreadthFirst;
use Graphp\Graph\Graph;
use Graphp\Tests\Algorithms\TestCase;

class BreadthFirstTest extends TestCase
{
    public function providerMaxDepth()
    {
        return array(
            "simple path (no limit)" => array(
                "edges" => array(
                    array(1, 2), array(2, 3), array(3, 4), array(4, 5),
                ),
                "subject" => 1,
                "maxDepth" => null,
                "expected" => array(1, 2, 3, 4, 5),
            ),
            "simple path (limit = 0)" => array(
                "edges" => array(
                    array(1, 2), array(2, 3), array(3, 4), array(4, 5),
                ),
                "subject" => 1,
                "maxDepth" => 0,
                "expected" => array(1),
            ),
            "simple path (limit = 1)" => array(
                "edges" => array(
                    array(1, 2), array(2, 3), array(3, 4), array(4, 5),
                ),
                "subject" => 1,
                "maxDepth" => 1,
                "expected" => array(1, 2),
            ),
        );
    }

    /**
     * @dataProvider providerMaxDepth
     */
    public function testMaxDepth(array $edges, $subject, $maxDepth, array $expected)
    {
        $g = new Graph();
        foreach ($edges as $e) {
            $g->createEdgeDirected($g->createVertex($e[0], true), $g->createVertex($e[1], true));
        }
        $a = new BreadthFirst($g->getVertex($subject));
        if ($maxDepth !== null) {
            $v = $a->getVertices($maxDepth);
        } else {
            $v = $a->getVertices(); // Simulate default
        }
        $this->assertSame($expected, $v->getIds());
    }
}
