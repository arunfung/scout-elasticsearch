<?php
/**
 * Created by PhpStorm.
 * User: arun
 * Date: 2019-07-19
 * Time: 11:46
 */

use ArunFung\ScoutElasticSearch\ElasticSearchEngine;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

class ElasticSearchEngineTest extends TestCase
{
    protected $index = 'test_index';

    protected function tearDown(): void
    {
        Mockery::close();
    }

    protected function setUp(): void
    {
        $elasticsearch = Elasticsearch\ClientBuilder::create()->setHosts([
            [
                'host' => '127.0.0.1',
                "port" =>  9200,
                'user' => '',
                'pass' => '',
                'scheme' => 'http',
            ],
        ])->build();
    }

    public function testElasticSearchEngineUpdate()
    {
        $testModel = Mockery::mock(Model::class);

        $testModel->shouldReceive([
                'searchableAs' => 'test_type',
                'getScoutKey'  => 1,
                'toSearchableArray' => ['id' => 1,'body' => 'test_body','content' => 'test_content']
            ]);
        $testModels = Collection::make([$testModel]);

        $elasticsearch = Mockery::mock(Elasticsearch\Client::class);

        $params['body'][] = [
            'index' => [
                '_index' => $this->index,
                '_type' => 'test_type',
                '_id' => 1
            ]
        ];
        $params['body'][] = ['id' => 1,'body' => 'test_body','content' => 'test_content'];

        $elasticsearch->shouldReceive('bulk')->with($params);

        $elasticSearchEngine = new ElasticSearchEngine($elasticsearch, $this->index);

        $elasticSearchEngine->update($testModels);
    }

    public function testElasticSearchEngineDelete()
    {
        $testModel = Mockery::mock(Model::class);

        $testModel->shouldReceive([
            'searchableAs' => 'test_type',
            'getScoutKey'  => 1,
            'toSearchableArray' => ['id' => 1,'body' => 'test_body','content' => 'test_content']
        ]);
        $testModels = Collection::make([$testModel]);

        $elasticsearch = Mockery::mock(Elasticsearch\Client::class);

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
}
