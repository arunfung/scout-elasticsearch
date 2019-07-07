<?php
/**
 * Created by PhpStorm.
 * User: arun
 * Date: 2019-07-07
 * Time: 12:59
 */

namespace ArunFung\ScoutElasticSearch;

use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;
use Elasticsearch\Client as ElasticSearch;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ElasticSearchEngine
 * @package ArunFung\ScoutElasticSearch
 */
class ElasticSearchEngine extends Engine
{
    /**
     * ElasticSearch instance
     *
     * @var ElasticSearch
     */
    protected $elasticSearch;

    /**
     * @var ElasticSearch Index
     */
    protected $index;

    /**
     * Create a new engine instance.
     *
     * @param ElasticSearch $elasticSearch
     * @param  $index
     * @return void
     */
    public function __construct(ElasticSearch $elasticSearch, $index)
    {
        $this->elasticSearch = $elasticSearch;
        $this->index = $index;
    }

    /**
     * Update the given model in the index.
     *
     * @param Collection $models
     * @return void
     */
    public function update($models)
    {
        $params = [];
        $models->each(function ($model) use (&$params) {
            $params['body'][] = [
                'index' => [
                    '_index' => $this->index,
                    '_type' => $model->searchableAs(),
                    '_id' => $model->getScoutKey()
                ]
            ];
            $params['body'][] = $model->toSearchableArray();
        });

        $this->elasticSearch->bulk($params);
    }

    /**
     * Remove the given model from the index.
     *
     * @param Collection $models
     * @return void
     */
    public function delete($models)
    {
        $params = [];
        $models->each(function ($model) use (&$params) {
            $params['body'][] = [
                'delete' => [
                    '_index' => $this->index,
                    '_type' => $model->searchableAs(),
                    '_id' => $model->getScoutKey()
                ]
            ];
        });
        $this->elasticSearch->bulk($params);
    }

    /**
     * Perform the given search on the engine.
     *
     * @param \Laravel\Scout\Builder $builder
     * @return mixed
     */
    public function search(Builder $builder)
    {
        // TODO: Implement search() method.
    }

    /**
     * Perform the given search on the engine.
     *
     * @param \Laravel\Scout\Builder $builder
     * @param int $perPage
     * @param int $page
     * @return mixed
     */
    public function paginate(Builder $builder, $perPage, $page)
    {
        // TODO: Implement paginate() method.
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param mixed $results
     * @return \Illuminate\Support\Collection
     */
    public function mapIds($results)
    {
        // TODO: Implement mapIds() method.
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param \Laravel\Scout\Builder $builder
     * @param mixed $results
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function map(Builder $builder, $results, $model)
    {
        // TODO: Implement map() method.
    }

    /**
     * Get the total count from a raw result returned by the engine.
     *
     * @param mixed $results
     * @return int
     */
    public function getTotalCount($results)
    {
        // TODO: Implement getTotalCount() method.
    }

    /**
     * Flush all of the model's records from the engine.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function flush($model)
    {
        // TODO: Implement flush() method.
    }
}
