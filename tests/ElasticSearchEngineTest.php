<?php
/**
 * Created by PhpStorm.
 * User: arun
 * Date: 2019-07-19
 * Time: 11:46
 */

use Mockery as m;
use ArunFung\ScoutElasticSearch\ElasticSearchEngine;
use Laravel\Scout\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;
use Elasticsearch\ClientBuilder as ElasticSearchBuilder;
use Elasticsearch\Client;

class ElasticSearchEngineTest extends TestCase
{
    protected $index = 'test_index';

    protected function tearDown(): void
    {
        m::close();
    }

    protected function setUp(): void
    {
        $elasticsearch = Elasticsearch\ClientBuilder::create()->setHosts([
            [
                'host' => '127.0.0.1',
                "port" => 9200,
                'user' => '',
                'pass' => '',
                'scheme' => 'http',
            ],
        ])->build();
    }

    public function testElasticSearchEngineUpdate()
    {
        $testModel = m::mock(Model::class);

        $testModel->shouldReceive([
            'searchableAs' => 'test_type',
            'getScoutKey' => 1,
            'toSearchableArray' => ['id' => 1, 'body' => 'test_body', 'content' => 'test_content']
        ]);
        $testModels = Collection::make([$testModel]);

        $elasticsearch = m::mock(Client::class);

        $params['body'][] = [
            'index' => [
                '_index' => $this->index,
                '_type' => 'test_type',
                '_id' => 1
            ]
        ];
        $params['body'][] = ['id' => 1, 'body' => 'test_body', 'content' => 'test_content'];

        $elasticsearch->shouldReceive('bulk')->with($params);

        $elasticSearchEngine = new ElasticSearchEngine($elasticsearch, $this->index);

        $elasticSearchEngine->update($testModels);
    }

    public function testElasticSearchEngineDelete()
    {
        $testModel = m::mock(Model::class);

        $testModel->shouldReceive([
            'searchableAs' => 'test_type',
            'getScoutKey' => 1,
            'toSearchableArray' => ['id' => 1, 'body' => 'test_body', 'content' => 'test_content']
        ]);
        $testModels = Collection::make([$testModel]);

        $elasticsearch = m::mock(Client::class);

        $params['body'][] = [
            'delete' => [
                '_index' => $this->index,
                '_type' => 'test_type',
                '_id' => 1
            ]
        ];

        $elasticsearch->shouldReceive('bulk')->with($params);

        $elasticSearchEngine = new ElasticSearchEngine($elasticsearch, $this->index);

        $elasticSearchEngine->delete($testModels);
    }

    public function testElasticSearchEngineSearch()
    {
        $builder = m::mock(Builder::class);
        $testModel = m::mock(Model::class);

        $testModel->shouldReceive([
            'searchableAs' => 'test_type',
        ]);

        $builder->query = 'test';
        $builder->model = $testModel;
        $builder->wheres = ["status" => 1];
        $builder->limit = 10;

        $params = [
            'index' => $this->index,
            'type' => 'test_type',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'query_string' => [
                                    'query' => "test"
                                ]
                            ],
                            ['match_phrase' => ['status' => 1]],
                        ]
                    ]
                ],
                'size' => 10,
            ]
        ];
        $elasticsearch = m::mock(Client::class);
        $elasticsearch->shouldReceive('search')->with($params);
        $elasticSearchEngine = new ElasticsearchEngine($elasticsearch, $this->index);

        $elasticSearchEngine->search($builder);
    }


    public function testElasticSearchEngineMapIds()
    {
        $results = [
            'hits' => [
                'hits' => [
                    ['_id' => 1],
                    ['_id' => 2],
                    ['_id' => 3],
                    ['_id' => 4],
                ],
            ],
        ];

        $elasticSearchEngine = new ElasticSearchEngine(ElasticSearchBuilder::create()->build(), $this->index);

        $this->assertEquals(
            [1, 2, 3, 4],
            $elasticSearchEngine->mapIds($results)->all()
        );
    }

    public function testElasticSearchEngineMap()
    {
        $builder = m::mock(Builder::class);
        $testModel = m::mock(Model::class);

        $testModel->shouldReceive('getScoutKey')->andReturn('1');
        $testModel->shouldReceive('getScoutModelsByIds')->with($builder, ['1'])->andReturn($models = Collection::make([$testModel]));

        $results = [
            'hits' => [
                "total" => [
                    "value" => 1,
                    "relation" => "eq"
                ],
                "max_score" => 1,
                "hits" => [
                    [
                        "_index" => $this->index,
                        "_type" => "scout",
                        "_id" => 1,
                        "_score" => 1,
                        "_source" => [
                            "id" => 1,
                        ]
                    ]
                ]
            ]
        ];

        $elasticsearch = m::mock(Client::class);
        $elasticSearchEngine = new ElasticsearchEngine($elasticsearch, $this->index);

        $data = $elasticSearchEngine->map($builder, $results, $testModel);

        $this->assertEquals(1, $data->count());

        $results = [
            'hits' => [
                "total" => [
                    "value" => 0,
                    "relation" => "eq"
                ],
                "max_score" => 1,
                "hits" => [
                ]
            ]
        ];
        $emptyData = $elasticSearchEngine->map($builder, $results, $testModel);

        $this->assertEquals(0, $emptyData->count());
    }

    public function testElasticSearchEngineGetTotalCount()
    {
        $results = ['hits' => ['total' => ['value' => 10]]];

        $elasticSearchEngine = new ElasticSearchEngine(ElasticSearchBuilder::create()->build(), $this->index);

        $this->assertEquals(
            10,
            $elasticSearchEngine->getTotalCount($results)
        );
    }
}
