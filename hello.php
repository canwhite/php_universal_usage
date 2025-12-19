<?php

require_once 'vendor/autoload.php';

use Zack\PhpUniversalUsage\HelloWorld;
use Zack\PhpUniversalUsage\HttpClient;
use Zack\PhpUniversalUsage\AsyncOperations;
use Zack\PhpUniversalUsage\PackageManagement;
use Zack\PhpUniversalUsage\TypeTesting;

echo "🚀 PHP语法和功能演示" . PHP_EOL;
echo "====================" . PHP_EOL . PHP_EOL;

// 1. 基础语法演示
$helloWorld = new HelloWorld();
$helloWorld->run();

echo PHP_EOL . PHP_EOL;

// 2. HTTP请求演示
$httpClient = new HttpClient();
$httpClient->demonstrateApiCalls();

// 3. 异步操作演示
$asyncOps = new AsyncOperations();
$asyncOps->runAll();

echo PHP_EOL;

// 4. 包管理演示
$packageManager = new PackageManagement();
$packageManager->runAll();

echo PHP_EOL;

// 5. 类型测试演示
$typeTesting = new TypeTesting();
$typeTesting->demonstrateTypeTesting();

echo PHP_EOL . PHP_EOL . "✨ 演示完成！" . PHP_EOL;