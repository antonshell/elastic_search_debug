<?php

namespace src;

/**
 * Class ProductSearch
 * @package CCXX\SearchEngine\Src\Elastic\Search\Index
 */
class ProductSearch
{
    const DEFAULT_FROM = 0;

    const DEFAULT_SIZE = 10;

    const DEFAULT_ORDER = 'asc';

    private $fields = [
        'sku' => [
            'boost' => 13,
        ],
        'name' => [
            'boost' => 9,
            'fuzziness'=> 'AUTO',
        ],
    ];

    /**
     * @param string $query
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function search(string $query, array $params = []) :array
    {
        $from = self::DEFAULT_FROM;
        $size = self::DEFAULT_SIZE;

        $matches = $this->buildMatches($query);
        $body = [
            'query' => [
                'bool' => [
                    'should' => $matches,
                ],
            ],
            'from' => $from,
            'size' => $size,
        ];

        // sort order
        $sortField = null;
        $sortOrder = $params['sort']['order'] ?? self::DEFAULT_ORDER;
        if($sortField && $sortOrder){
            $body['sort'] = [
                $sortField => [
                    'order' => $sortOrder,
                ]
            ];
        }

        // category
        $categoryId = $params['category_id'] ?? null;
        if($categoryId){
            $body['query']['bool']['filter'] = [
                'bool' => [
                    'must' => [
                        'match' => [
                            'category_ids' => $categoryId
                        ]
                    ]
                ]
            ];
        }

        $body = json_encode($body);

        // config
        $config = Config::load();
        $baseUrl = $config['elastic']['base_url'];
        $index = $config['elastic']['index'];
        $url = $baseUrl . '/' . $index . '/type/_search';

        $client = new Curl();
        $results = $client->sendRequest($url, 'POST', $body, ['Content-Type: application/json']);

        if(!is_array($results)){
            throw new \Exception('Error. Cant get data from elastic');
        }

        return $results;
    }

    /**
     * @param $results
     * @return array
     * @throws \Exception
     */
    public function processResults($results){
        $data = [];

        if(!isset($results['hits']['hits'])){
            throw new \Exception('Error. Elastic returned wrong data structure');
        }

        $hits = $results['hits']['hits'];

        foreach ($hits as $item){
            $source = $item['_source'];
            $data[] = [
                'sku' => $source['sku'],
                'name' => $source['name'],
            ];
        }

        return $data;
    }

    /**
     * @param $query
     * @return array
     */
    private function buildMatches($query){
        $matches = [];

        foreach ($this->fields as $field => $settings){
            $match = [
                'match' => [
                    $field => $settings
                ]
            ];

            $match['match'][$field]['query'] = $query;
            $matches[] = $match;
        }

        return $matches;
    }
}