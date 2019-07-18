<?php
/**
 * Created by PhpStorm.
 * User: arun
 * Date: 2019-07-16
 * Time: 10:35
 */

namespace ArunFung\ScoutElasticSearch\Console\Commands;

use Elasticsearch\ClientBuilder as ElasticSearchBuilder;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\Config;

/**
 * Class CreateElasticSearchIndex
 * @package App\Console\Commands
 */
class CreateElasticSearchIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:create-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create elasticSearch index';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $elasticSearch = ElasticSearchBuilder::create()->setHosts(Config::get('elasticsearch.hosts'))->build()->indices();

        $index = Config::get('elasticsearch.index');
        if ($elasticSearch->exists(['index' => $index])) {
            $this->error(sprintf('The "%s" index already exists', $index));
            return;
        }
        $params = Config::get('elasticsearch.' . $index);

        if (!empty($params)) {
            try {
                $elasticSearch->create($params);
            } catch (Exception $e) {
                $this->error($e->getMessage());
                return;
            }
            $this->info(sprintf('"%s" index created successfully', $index));
            return;
        } else {
            $this->warn(sprintf('"%s" index configuration not found', $index));
            return;
        }
    }
}
