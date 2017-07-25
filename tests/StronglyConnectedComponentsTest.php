<?php

use Graphp\Algorithms\StronglyConnectedComponents;
use Fhaculty\Graph\Edge\Directed;
use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Set\Vertices;

class StronglyConnectedComponentsTest extends TestCase
{
    /**
     * @link https://en.wikipedia.org/wiki/Tarjan%27s_strongly_connected_components_algorithm
     * @link https://en.wikipedia.org/wiki/Strongly_connected_component#Algorithms
     *
     * @return array
     */
    public function providerCyclicDigraphs()
    {
        return array(
            /** @link https://en.wikipedia.org/wiki/Strongly_connected_component#/media/File:Scc.png */
            '1' => array(
                'edges' => array(
                    array(1,2),
                    array(2,3), array(2,5), array(2,6),
                    array(3,4), array(3,7),
                    array(4,3), array(4,8),
                    array(5,1), array(5,6),
                    array(6,7),
                    array(7,6),
                    array(8,4), array(8,7),
                ),
                'sets' => array(
                    array(1,2,5),
                    array(3,4,8),
                    array(6,7),
                ),
            ),
            /** @link https://en.wikipedia.org/wiki/File:Tarjan%27s_Algorithm_Animation.gif */
            '2' => array(
                'edges' => array(
                    array(1,2),
                    array(2,3),
                    array(3,1),
                    array(4,2), array(4,3), array(4,5),
                    array(5,4), array(5,6),
                    array(6,3), array(6,7),
                    array(7,6),
                    array(8,5), array(8,7),
                    // array(8,8), /* @link https://github.com/clue/graph/issues/154 */
                ),
                'sets' => array(
                    array(1,2,3),
                    array(4,5),
                    array(6,7),
                    array(8),
                ),
            ),
        );
    }

    /**
     * @param Graph $graph
     * @param array $edge
     * @return Directed
     */
    public function addEdgeToGraph(Graph $graph, array $edge)
    {
        $from = $graph->createVertex($edge[0], true);
        $to = $graph->createVertex($edge[1], true);
        return $from->createEdgeTo($to);
    }

    /**
     * @dataProvider providerCyclicDigraphs
     * @param array $edges
     * @param array $expected
     */
    public function testStronglyConnectedSets(array $edges, array $expected)
    {
        $graph = new Graph();

        foreach ($edges as $edge) {
            $this->addEdgeToGraph($graph, $edge);
        }

        $alg = new StronglyConnectedComponents();
        $sets = array_map(function (Vertices $vertices) {
            return $vertices->getIds();
        }, $alg->stronglyConnectedVertices($graph));

        // Assert unordered array equals
        $this->assertEquals($expected, $sets, "\$canonicalize = true", 0.0, 2, true);
    }
}