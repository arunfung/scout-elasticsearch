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
use Illuminate\Database\Eloquent\Model;
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
     * @param Builder $builder
     * @return mixed
     */
    public function search(Builder $builder)
    {
        return $this->performSearch($builder, array_filter([
            'numericFilters' => $this->filters($builder),
            'size' => $builder->limit,
        ]));
    }

    /**
     * Perform the given search on the engine.
     *
     * @param Builder $builder
     * @param int $perPage
     * @param int $page
     * @return mixed
     */
    public function paginate(Builder $builder, $perPage, $page)
    {
        $options = [
            'numericFilters' => $this->filters($builder),
            'from' => (($page - 1) * $perPage),
            'size' => $perPage,
        ];

        return $this->performSearch($builder, $options);
    }

    /**
     * Perform the given search on the engine.
     *
     * @param Builder $builder
     * @param array $options
     * @return mixed
     */
    protected function performSearch(Builder $builder, array $options = [])
    {
        $params = [
            'index' => $this->index,
            'type' => $builder->model->searchableAs(),
        ];

        $must = [
            [
                'query_string' => ['query' => "{$builder->query}"]
            ]
        ];

        if (isset($options['numericFilters']) && count($options['numericFilters'])) {
            $must = array_merge($must, $options['numericFilters']);
        }

        $body = [
            'query' => [
                'bool' => [
                    'must' => $must
                ]
            ]
        ];

        if (isset($options['from'])) {
            $body['from'] = $options['from'];
        }

        if (isset($options['size'])) {
            $body['size'] = $options['size'];
        }

        $params['body'] = $body;

        if ($builder->callback) {
            return call_user_func(
                $builder->callback,
                $this->elasticSearch,
                $builder->query,
                $params
            );
        }

        return $this->elasticSearch->search($params);
    }

    /**
     * Get the filter array for the query.
     *
     * @param Builder $builder
     * @return array
     */
    protected function filters(Builder $builder)
    {
        return collect($builder->wheres)->map(function ($value, $key) {
            if (is_array($value)) {
                return ['terms' => [$key => $value]];
            }
            return ['match_phrase' => [$key => $value]];
        })->values()->all();
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param mixed $results
     * @return \Illuminate\Support\Collection
     */
    public function mapIds($results)
    {
        return collect($results['hits']['hits'])->pluck('_id');
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param Builder $builder
     * @param mixed $results
     * @param Model $model
     * @return Collection
     */
    public function map(Builder $builder, $results, $model)
    {
        if ($this->getTotalCount($results) == 0) {
            return Collection::make();
        }

        $ids = $this->mapIds($results)->all();
        $objectIdPositions = array_flip($ids);

        /**
         * @var Collection $models
         */
        $models = $model->getScoutModelsByIds($builder, $ids);

        return $models->filter(function ($model) use ($ids) {
            return in_array($model->getScoutKey(), $ids);
        })->sortBy(function ($model) use ($objectIdPositions) {
            return $objectIdPositions[$model->getScoutKey()];
        })->values();
    }

    /**
     * Get the total count from a raw result returned by the engine.
     *
     * @param mixed $results
     * @return int
     */
    public function getTotalCount($results)
    {
        return $results['hits']['total']['value'];
    }

    /**
     * Flush all of the model's records from the engine.
     *
     * @param Model $model
     * @return void
     */
    public function flush($model)
    {
        $model->newQuery()->orderBy($model->getKeyName())->unsearchable();
    }
}
