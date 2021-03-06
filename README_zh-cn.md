<p align="center">
<a href="https://travis-ci.com/arunfung/scout-elasticsearch"><img src="https://travis-ci.com/arunfung/scout-elasticsearch.svg" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/?branch=master"><img src="https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/badges/quality-score.png?b=master" alt="Scrutinizer Code Quality"></a>
<a href="https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/?branch=master"><img src="https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/badges/coverage.png?b=master" alt="Code Coverage"></a>
<a href="https://packagist.org/packages/arunfung/scout-elasticsearch"><img src="https://poser.pugx.org/arunfung/scout-elasticsearch/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/arunfung/scout-elasticsearch"><img src="https://poser.pugx.org/arunfung/scout-elasticsearch/v/stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/arunfung/scout-elasticsearch"><img src="https://poser.pugx.org/arunfung/scout-elasticsearch/license" alt="License"></a>
</p>

# scout-elastic-search

## 介绍

目前，laravel Scout 只支持 Algolia 驱动程序，这个包是 laravel Scout 的 ElasticSearch 驱动程序。

## 安装

通过 composer 安装:

``` bash
composer require arunfung/scout-elasticsearch
```

如果使用的 Laravel 版本小于 5.5 或禁用了包发现，则必须在 “app.php” 中添加 Scout 服务提供者和这个包的服务提供者。
```php
/*
 * Package Service Providers...
 */
Laravel\Scout\ScoutServiceProvider::class,
ArunFung\ScoutElasticSearch\ElasticSearchServiceProvider::class,
```

## 配置

- 发布配置

```php
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
php artisan vendor:publish --provider="ArunFung\ScoutElasticSearch\ElasticSearchServiceProvider"
```

- 在 `.env` 设置 scout 驱动

```php
// 设置 scout 驱动为 elasticsearch
SCOUT_DRIVER=elasticsearch
```

- 创建一个新的 ElasticSearch 索引

如果需要自定义`index mapping` 你可以在 `config/elasticsearch.php` 中设置

```php
// 将索引名配置添加到 .env
ELASTIC_SEARCH_INDEX=index name

// 创建通用索引及映射
php artisan es:create-index
```

## 使用

有关 Scout 的使用文档可以在 [Laravel 中文网站](https://learnku.com/docs/laravel/5.8/scout/3946) 上查找。
