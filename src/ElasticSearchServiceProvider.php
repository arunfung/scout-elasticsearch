<?php
/**
 * Created by PhpStorm.
 * User: arun
 * Date: 2019-07-07
 * Time: 13:00
 */

namespace ArunFung\ScoutElasticSearch;

use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;
use Elasticsearch\ClientBuilder as ElasticSearchBuilder;

/**
 * Class ElasticSearchServiceProvider
 * @package ArunFung\ScoutElasticSearch
 */
class ElasticSearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/elasticsearch.php', 'elasticsearch'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/elasticsearch.php' => config_path('elasticsearch.php'),
        ]);

        resolve(EngineManager::class)->extend('elasticsearch', function () {
            return new ElasticSearchEngine(
                ElasticSearchBuilder::create()->setHosts(config('elasticsearch.hosts'))->build(),
                config('elasticsearch.index')
            );
        });
    }
}
