[![Build Status](https://travis-ci.com/arunfung/scout-elasticsearch.svg?branch=master)](https://travis-ci.com/arunfung/scout-elasticsearch)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/arunfung/scout-elasticsearch/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/arunfung/scout-elasticsearch/v/stable)](https://packagist.org/packages/arunfung/scout-elasticsearch)
[![Total Downloads](https://poser.pugx.org/arunfung/scout-elasticsearch/downloads)](https://packagist.org/packages/arunfung/scout-elasticsearch)
[![License](https://poser.pugx.org/arunfung/scout-elasticsearch/license)](https://packagist.org/packages/arunfung/scout-elasticsearch)

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
