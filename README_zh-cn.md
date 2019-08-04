[![Build Status](https://travis-ci.com/arunfung/scout-elasticsearch.svg?branch=master)](https://travis-ci.com/arunfung/scout-elasticsearch)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/arunfung/scout-elasticsearch/v/stable)](https://packagist.org/packages/arunfung/scout-elasticsearch)
[![Total Downloads](https://poser.pugx.org/arunfung/scout-elasticsearch/downloads)](https://packagist.org/packages/arunfung/scout-elasticsearch)
[![License](https://poser.pugx.org/arunfung/scout-elasticsearch/license)](https://packagist.org/packages/arunfung/scout-elasticsearch)

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

- 创建一个新的 ElasticSearch 索引

```php
// 将索引名配置添加到 .env
ELASTIC_SEARCH_INDEX=index name

// 创建通用索引及映射
php artisan es:create-index
```

## 使用

有关 Scout 的使用文档可以在 [Laravel 中文网站](https://learnku.com/docs/laravel/5.8/scout/3946) 上查找。
