<p align="center">
<a href="https://travis-ci.com/arunfung/scout-elasticsearch"><img src="https://travis-ci.com/arunfung/scout-elasticsearch.svg" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/?branch=master"><img src="https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/badges/quality-score.png?b=master" alt="Scrutinizer Code Quality"></a>
<a href="https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/?branch=master"><img src="https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/badges/coverage.png?b=master" alt="Code Coverage"></a>
<a href="https://packagist.org/packages/arunfung/scout-elasticsearch"><img src="https://poser.pugx.org/arunfung/scout-elasticsearch/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/arunfung/scout-elasticsearch"><img src="https://poser.pugx.org/arunfung/scout-elasticsearch/v/stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/arunfung/scout-elasticsearch"><img src="https://poser.pugx.org/arunfung/scout-elasticsearch/license" alt="License"></a>
</p>

# scout-elastic-search

[English](https://github.com/arunfung/scout-elasticsearch) | 
[中文](https://github.com/arunfung/scout-elasticsearch/blob/master/README_zh-cn.md)

## Introduction

Currently, laravel Scout only supports Algolia driver,
This package is ElasticSearch driver for laravel Scout.

## Installation

Install the package via composer:

``` bash
composer require arunfung/scout-elasticsearch
```

If you are using Laravel version < 5.5 or the package discovery is disabled, Must add the Scout service provider and the package service provider in your `app.php`

```php
/*
 * Package Service Providers...
 */
Laravel\Scout\ScoutServiceProvider::class,
ArunFung\ScoutElasticSearch\ElasticSearchServiceProvider::class,
```

## Configuration

- Publish settings

```php
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
php artisan vendor:publish --provider="ArunFung\ScoutElasticSearch\ElasticSearchServiceProvider"
```

- Create a new ElasticSearch index

```php
// add index name into .env
ELASTIC_SEARCH_INDEX=index name

// Create a generic index mapping
php artisan es:create-index
```

## Usage

Documentation for Scout can be found on the [Laravel website](https://laravel.com/docs/master/scout).
